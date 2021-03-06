<?php

namespace App\Http\Controllers;

use DB;
use URL;
use Auth;
use Excel;
use Session;
use Response;
use App\Article;
use App\Category;
use App\Location;
use App\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //       
        $articles = Article::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        //$records = Record::where('moved','=',false)->paginate();
        $records = Record::whereMoved(false)->paginate();
        return view('records.index',compact('records','locations','articles'));
    }

    /**
     * Display inventory.
     *
     * @return \Illuminate\Http\Response
     */
    public function inventory()
    {      
        $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
            ->join('locations', 'records.location_id', '=', 'locations.id')
            ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
            ->groupBy('articles.name','articles.id','locations.country')
            ->get();
        $categories = Category::orderBy('name')->get();

        $locations = Location::orderBy('name')->get();
        $articles = Article::orderBy('name')->paginate();
        return view('records.inventory',compact('articles','locations','records','categories'));
    }

    /**
     * Create a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($motive,$article_id,$location_id,$quantity,$comment)
    {
        //
        $record = new Record();
        $record->motive = $motive;
        $record->date = date("Y-m-d");
        $record->article_id = $article_id;
        $record->location_id = $location_id;
        $record->quantity = $quantity;
        $record->comment = $comment;
        $record->moved = true;
        $record->user_id = Auth::id();

        $record->save();

        return $record;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        Session::put('PreviousURL', URL::previous());
        $this->validate($request, [
            'motive' => 'required',
            'date' => 'required',
            'article_id' => 'required',
            'location_id' => 'required',
            'quantity' => 'required'
        ]);

        $record = new Record($request->all());

        $record->date = $record->getFormatDate($record->date);
        $record->user_id = Auth::id();
        
        $saved = $record->save();

        $article = Article::find($request->article_id);

        $done = $this->updateStock($record,$article,$request->motive,$request->quantity,$request->location_id);

        if ($saved and $done) {
            $request->session()->flash('flash_message', 'Movimiento agregado.');
        }
        elseif (!$done) {
            $request->session()->flash('flash_message_not', 'Movimiento no permitido no hay la cantidad suficiente. Disponible: '. $article->stock);
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo agregar el movimiento.');
        }

        return redirect(Session::get('PreviousURL'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $record = Record::find($id);
        if (is_null($record))
        {
            return redirect('/record');
        }
        
        $record->append('name')->toArray();

        return Response::json($record);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        //dd($request);
        $this->validate($request, [
            'motive' => 'required',
            'date_edit' => 'required',
            'article_id' => 'required',
            'location_id' => 'required',
            'quantity' => 'required'
        ]);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Record $record)
    {
        //dd($record);
        if ($record->moved) {
            $request->session()->flash('flash_message_not', 'No se puede eliminar el movimiento ya que pertenece a un traslado.');
        } else {
            $article = Article::find($record->article_id);
            
            $done = $this->updateStock($record,$article,(($record->motive == 'entrada') ? 'salida' : 'entrada'),$record->quantity,$record->location_id);

            if ($done) {
                $deleted = $record->delete();

                if ($deleted) {
                    $request->session()->flash('flash_message', 'Movimiento eliminado.');
                }
                else{
                    $request->session()->flash('flash_message_not', 'No se pudo eliminar el movimiento.'); 
                }
            } 
            else {
                
            }           
        } 
        return back();
        
    }

    /**
     * Update stock of products after a CRUD operation.
     *
     * @param  \App\Record  $record
     * @param  \App\Article  $article
     * @param  string  $motive
     * @param  int  $quantity
     * @param  int  $location_id
     * @return boolean 
     */
    public function updateStock(Record $record,Article $article,$motive,$quantity,$location_id)
    {
        # code...
        $record->quantity = ($motive == 'entrada') ? $quantity : ($quantity*(-1));
        $article->increment('stock',$record->quantity);
        if ($article->stock < 0){
            $record->delete();
            return false;
        }
        else {
            $article->update();

            $locations = $article->locations()->get();
            
            if ($locations->isEmpty() or !$locations->contains('id',$location_id)) {
                if ($record->quantity > 0) {
                   $article->locations()->attach($location_id,['stock'=> $record->quantity]);
                } else {
                    $record->delete();
                    return false;
                }               
            } else {
                foreach ($locations as $location) {
                    if ($location->id == $location_id) {
                        $qty = ($location->pivot->stock + $record->quantity);
                        if ($qty < 0) {
                           $record->delete();
                            return false;
                        } else {
                            $article->locations()->updateExistingPivot($record->location_id,['stock' => $qty]);
                        }                       
                    }                    
                }                
            }
            return true;
        }           
    }

    /**
     * Search the specified resource(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //dd($request); 

        $this->validate($request,[
            'date_from' => 'required_if:search_record,date',
            'date_to' => 'required_if:search_record,date'
        ]);

        $parameter = $request->search_record;
        $query = $request->value;

        if ($parameter == 'date'){
            $records = Record::whereBetween('date',[$this->formatDate($request->date_from),$this->formatDate($request->date_to)])
                ->when(!$request->has('moved'), function ($q){
                        $q->whereMoved(false);
                    })->paginate();
        }  
        elseif ($parameter == '' and $query == '') {
            $records = Record::when(!$request->has('moved'), function ($q){
                        $q->whereMoved(false);
                    })->paginate();
        } 
        elseif ($parameter == '' and $query != '') {
            $records = Record::with('article')->with('location')
                ->where('motive','LIKE', $query . '%')
                ->orWhere('comment','LIKE', $query . '%')
                ->orWhereHas('article', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->orWhereHas('location', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->when(!$request->has('moved'), function ($q){
                        $q->whereMoved(false);
                    })
                ->join('articles','records.article_id','=','articles.id')
                ->join('locations','records.location_id','=','locations.id')
                ->select('records.*')
                ->paginate();
        }
        else {
            $records = Record::where($parameter, 'LIKE', '%' . $query . '%')
                ->when(!$request->has('moved'), function ($q){
                        $q->whereMoved(false);
                    })
                ->paginate();      
        }

        if($records->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $articles = Article::orderBy('name')->get();
            $locations = Location::orderBy('name')->get();
            return view('records.index', compact('articles','locations','records'));
        }           
    }

    /**
     * Search inventory of products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchInventory(Request $request)
    {
        if ($request->has('category')) {
            $articles = Article::whereIn('category_id',$request->category)->join('categories','articles.category_id','=','categories.id')->orderBy('categories.name')->orderBy('articles.name')->select('articles.*')->paginate();

            if (!$articles->isEmpty()) {
                $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
                ->join('locations', 'records.location_id', '=', 'locations.id')
                ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
                ->groupBy('articles.name','articles.id','locations.country')
                ->get();
            } else {
               return back()->with('flash_message_info', 'No hay resultados para el filtro aplicado.');
            }            
        } else {
            $articles = Article::orderBy('name')->paginate();
            $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
            ->join('locations', 'records.location_id', '=', 'locations.id')
            ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
            ->groupBy('articles.name','articles.id','locations.country')
            ->get();
        }
                  
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        return view('records.inventory', compact('articles','locations','records','categories'));
    }

    /**
     * Export all resources.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);

        Excel::create('Movimientos', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $records = Record::whereMoved(false)->get();             
 
                $sheet->fromArray($records);
 
            });
        })->export($request->extension);
    }

    /**
     * Import resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'file' => 'file'
        ]);        
        
        $count = 0;

        if($request->hasFile('records_file')){
            $path = $request->file('records_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            
            if(!empty($data) and $data->count()){
                $records = Record::whereMoved(false)->get();
                                
                foreach ($data as $row) {
                    if (!$records->contains('id',$row->id)) {
                        $record = $this->create($row->motive,$row->article_id,$row->location_id,$row->quantity,$row->comment);
                        
                        $record->moved = false;
                        $record->save();
                        $article = Article::find($record->article_id);
                        
                        $done = $this->updateStock($record,$article,$record->motive,$record->quantity,$record->location_id);

                        if ($done) {
                            $count++;
                        } else {
                           $record->delete();
                        }
                    }                    
                }
            }
        } else {
            return back()->with('flash_message_not', 'No se cargó ningún archivo.');
        }

        if ($count > 0) {
            $request->session()->flash('flash_message', 'Se importaron '.$count.' registros correctamente.');
        } else {
            $request->session()->flash('flash_message_info', 'No habían registros por importar.');
        }
        
        return back();      
    }

    /**
     * Move certain quantity of the specified article from one location to another.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request,Article $article)
    {
        //dd($request);
        $this->validate($request,[
            'origin' => 'required',
            'destination' => 'required',
            'quantity' => 'required'
        ]);

        $origin = Location::find($request->origin);
        $destination = Location::find($request->destination);
        $stock_origin = $stock_destination = 0;

        $locations = $article->locations()->get();
        foreach ($locations as $location) {
            if ($location->id == $origin->id) {
                $stock_origin = $location->pivot->stock;
            } 
            if ($location->id == $destination->id){
               $stock_destination = $location->pivot->stock;
            }            
        }

        if ($article->stock == 0) {
            $request->session()->flash('flash_message_not', 'El artículo seleccionado no tiene stock disponible.'); 
        } 
        elseif($request->origin == $request->destination) {
            $request->session()->flash('flash_message_not', 'El origen no puede ser igual al destino.'); 
        }
        else {    
            $exit = $this->create('salida',$article->id,$request->origin,$request->quantity,$request->comment);
            $done_exit = $this->updateStock($exit,$article,'salida',$request->quantity,$request->origin);

            if ($done_exit) {
                $entry = $this->create('entrada',$article->id,$request->destination,$request->quantity,$request->comment);
                $done_entry = $this->updateStock($entry,$article,'entrada',$request->quantity,$request->destination);
            }
            else {
                return back()->with('flash_message_not', 'El origen no tiene la cantidad suficiente para realizar el traslado.
                    Disponible en '.$origin->name.': '.$stock_origin);
            }

            if ($done_entry and $done_exit){
                $request->session()->flash('flash_message', 'Artículo trasladado.');
            } else {
                $request->session()->flash('flash_message_not', 'No se pudo realizar el traslado.');
            }
        }
        return back();        
    }

    /**
     * Change format of date to be able to store it in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function formatDate($value)
    {
       return date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }
}




