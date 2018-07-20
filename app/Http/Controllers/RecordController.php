<?php

namespace App\Http\Controllers;

use DB;
use URL;
use Auth;
use Excel;
use Session;
use Response;
use App\Article;
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
        $records = Record::where('moved','=',false)->paginate(20);
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

        //dd($query);
        $locations = Location::orderBy('name')->get();
        $articles = Article::orderBy('name')->paginate(7);
        return view('records.inventory',compact('articles','locations','records'));
    }

    /**
     * Show the form for creating a new resource.
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
        //
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
        //
        //dd($request);
        $this->validate($request, [
            'motive' => 'required',
            'date_edit' => 'required',
            'article_id' => 'required',
            'location_id' => 'required',
            'quantity' => 'required'
        ]);

        $data = $request->all(); 
        
        $data['date'] = $record->getFormatDate($request->date_edit);   
        //dd($data);

        $article = Article::find($request->article_id);

        if ($request->quantity == $record->quantity and $request->motive != $record->motive and $request->location_id == $record->location_id){            
            $done = $this->updateStock($record,$article,$request->motive);
        }
        elseif ($request->quantity != $record->quantity and $request->motive == $record->motive and $request->location_id == $record->location_id){
            $motive = ($request->motive == 'entrada') ? 'salida' : 'entrada';
            $done = $this->updateStock($record,$article,$motive);

            $record->quantity = $request->quantity;
            //dd($record);
            $done = $this->updateStock($record,$article,$request->motive);
        }
        elseif ($request->quantity != $record->quantity and $request->motive == $record->motive and $request->location_id == $record->location_id){

        }
        $saved = $record->update($data);

        if ($saved) {
            $request->session()->flash('flash_message', 'Movimiento modificado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar el Movimiento.');
        }

        return redirect('/record');
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
        //        dd($record);
        if ($record->moved) {
            $request->session()->flash('flash_message_not', 'No se puede eliminar el movimiento ya que pertenece a un traslado.');
        } else {
            $article = Article::find($record->article_id);
            if ($record->motive == 'entrada') {
                $this->updateStock($record,$article,'salida',$record->quantity,$record->location_id);

                $deleted = $record->delete();            
            } else {
                $this->updateStock($record,$article,'entrada',$record->quantity,$record->location_id);

                $deleted = $record->delete();
            }

            if ($deleted) {
                $request->session()->flash('flash_message', 'Movimiento eliminado.');
            }
            else{
                $request->session()->flash('flash_message_not', 'No se pudo eliminar el movimiento.'); 
            }
        }  
        //return redirect('/record');
        return back();
        
    }

    public function updateStock(Record $record,Article $article,$motive,$quantity,$location_id)
    {
        # code...

        $record->quantity = ($motive == 'entrada') ? $quantity : ($quantity*(-1));
        $article->stock = $article->stock + $record->quantity;
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

    public function search(Request $request)
    {
        //dd($request);   
        $parameter = $request->search_record;
        $query = $request->value;
        
        if ($parameter == '' and $query == '') {
            $records = Record::when(!$request->has('moved'), function ($q){
                        $q->where('moved','=', false);
                    })->paginate(20);
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
                        $q->where('moved','=', false);
                    })
                ->join('articles','records.article_id','=','articles.id')
                ->join('locations','records.location_id','=','locations.id')
                ->select('records.*')
                ->paginate(20);
        }
        elseif ($parameter == 'date') {
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_search)));
            $records = Record::where('date','=', $date)
                ->when(!$request->has('moved'), function ($q){
                        $q->where('moved','=', false);
                    })
                ->paginate(20);
        }
        else {
            $records = Record::where($parameter, 'LIKE', '%' . $query . '%')
                ->when(!$request->has('moved'), function ($q){
                        $q->where('moved','=', false);
                    })
                ->paginate(20);      
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

    public function searchInventory(Request $request)
    {
        //dd($request);

        /*$query = DB::table('records')            
            ->join('articles', 'records.article_id', '=', 'articles.id')
            ->join('locations', 'records.location_id', '=', 'locations.id')
            ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
            ->groupBy('articles.name','articles.id','locations.country')
            ->get();*/

        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' and $query == '') {
            return redirect('inventory');
        }
        elseif ($parameter == '' and $query != '') {
            $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
                ->join('locations', 'records.location_id', '=', 'locations.id')
                ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
                ->whereHas('article', function ($q) use ($query){
                    $q->orWhere('name','LIKE', '%' . $query . '%')
                        ->orWhereHas('category', function($f) use ($query){
                            $f->where('name','LIKE', '%' . $query . '%');
                        })
                        ->orWhereHas('product', function($f) use ($query){
                            $f->where('name','LIKE', '%' . $query . '%');
                        });
                    })
                ->orWhereHas('location', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })            
                ->groupBy('articles.name','articles.id','locations.country')
                ->get();
        }
        elseif ($parameter == 'article'){
            $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
                ->join('locations', 'records.location_id', '=', 'locations.id')
                ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
                ->whereHas('article', function ($q) use ($query){
                    $q->where('name','LIKE', '%' . $query . '%');
                })
                ->groupBy('articles.name','articles.id','locations.country')
                ->toSql();
        }
        elseif ($parameter == 'category'){
            $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
                ->join('locations', 'records.location_id', '=', 'locations.id')
                ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
                ->whereHas('article', function ($q) use ($query){
                    $q->whereHas('category', function($f) use ($query){
                            $f->where('name','LIKE', '%' . $query . '%');
                        });
                })
                ->groupBy('articles.name','articles.id','locations.country');
            $articles = Article::whereHas('category', function($f) use ($query){
                            $f->where('name','LIKE', '%' . $query . '%');
                        })
                    ->select(DB::raw('0.00 AS stock'),'articles.name','articles.id',DB::raw('"" as country' ))
                    ->whereNotIn('id',$records->getQuery())
                    ->union($records)
                    ->get();
        }
        else {
             $records = Record::join('articles', 'records.article_id', '=', 'articles.id')
                ->join('locations', 'records.location_id', '=', 'locations.id')
                ->select(DB::raw('SUM(CASE WHEN motive = "entrada" THEN quantity ELSE -quantity END) AS stock'),'articles.name','articles.id','locations.country')
                ->where($parameter, 'LIKE', '%' . $query . '%')
                ->toSql();

        }

        //dd($records);
        if($records->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $array = [];
            foreach ($records as $record) {
                array_push($array, $record->id);
            }
            //dd($array);
            
            $articles = Article::whereIn('id',$array)->orderBy('name')->get();
            //dd($articles);
            $locations = Location::orderBy('name')->get();
            return view('records.inventory', compact('articles','locations','records'));
        } 
    }

    public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);

        Excel::create('Movimientos', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $records = Record::all();             
 
                $sheet->fromArray($records);
 
            });
        })->export($request->extension);
    }

    public function import(Request $request)
    {
        # code...
        $this->validate($request, [
            'file' => 'file'
        ]);        
        
        $count = 0;

        if($request->hasFile('locations_file')){
            $path = $request->file('locations_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            
            if(!empty($data) && $data->count()){
                $locations = Location::all();
                                
                foreach ($data as $location) {
                    if (!$locations->contains('id',$location->id)) {
                       Location::create([
                            'name' => $location->name,
                            'telephone' => $location->telephone,
                            'in_charge' => $location->in_charge,
                            'country' => $location->country,
                            'user_id' =>$location->user_id,
                        ]);
                       $count++;
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

    public function move(Request $request,Article $article)
    {
        # code...
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
}
