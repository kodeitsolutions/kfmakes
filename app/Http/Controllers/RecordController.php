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
        $records = Record::paginate(20);
        return view('records.index',compact('records','locations','articles'));

    }

    /**
     * Display inventory.
     *
     * @return \Illuminate\Http\Response
     */
    public function inventory()
    {    
        $query = DB::table('articles')
            ->join('article_location', 'article_location.article_id', '=', 'articles.id')
            ->join('locations', 'article_location.location_id', '=', 'locations.id')
            ->select('articles.name','articles.id','locations.country',DB::raw('SUM(article_location.stock) AS stock'))
            ->groupBy('articles.name','articles.id','locations.country')
            ->get();
        //dd($query);
        $locations = Location::orderBy('name')->get();
        $articles = Article::orderBy('name')->paginate(7);
        return view('records.inventory',compact('articles','locations','query'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $done = $this->updateStock($record,$article,$request->motive);

        if ($saved and $done) {
            $request->session()->flash('flash_message', 'Movimiento agregado.');
        }
        elseif (!$done) {
            $request->session()->flash('flash_message_not', 'Movimiento no permitido no hay la cantidad suficiente.');
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
        dd($request);
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

        if ($request->quantity == $record->quantity and $request->motive != $record->motive){
            $article = Article::find($request->article_id);
            $this->updateStock($record,$article,$request->motive);
        }
        elseif ($request->quantity != $record->quantity and $request->motive == $record->motive){

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
        //
        $article = Article::find($record->article_id);
        if ($record->motive == 'entry') {
            $this->updateStock($record,$article,'exit');

            $deleted = $record->delete();            
        } else {
            $this->updateStock($record,$article,'entry');

            $deleted = $record->delete();
        }

        if ($deleted) {
            $request->session()->flash('flash_message', 'Movimiento eliminado.');
        }
        else{
            $request->session()->flash('flash_message_not', 'No se pudo eliminar el movimiento.'); 
        }

        return redirect('/record');
        
    }

    public function updateStock(Record $record,Article $article,$motive)
    {
        # code...
        $record->quantity = ($motive == 'entrada') ? $record->quantity : ($record->quantity*(-1));
        $article->stock = $article->stock + $record->quantity;
        if ($article->stock < 0){
            $record->delete();
            return false;
        }
        else {
            $article->update();

            $locations = $article->locations()->get();
            
            if ($locations->isEmpty() or !$locations->contains('id',$record->location_id)) {
                $article->locations()->attach($record->location_id,['stock'=> $record->quantity]);
            } else {
                foreach ($locations as $location) {
                    $quantity = ($location->pivot->stock + $record->quantity);
                    $article->locations()->updateExistingPivot($record->location_id,['stock' => $quantity]);
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
        
        if ($parameter == '' && $query == '') {
            $records = Record::paginate(20);
        } 
        elseif ($parameter == '' && $query != '') {
            $records = Record::where('motive','LIKE', $query . '%')
                ->orWhere('comment','LIKE', $query . '%')
                ->orWhereHas('article', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->orWhereHas('location', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->join('articles','records.article_id','=','articles.id')
                ->join('locations','records.location_id','=','locations.id')
                ->select('records.*')
                ->paginate(7);
        }
        elseif ($parameter == 'date') {
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $request->date_search)));
            //dd($date);
            $records = Record::where('date','=', $date)
                ->paginate(7);
        }
        else {
            $records = Record::where($parameter, 'LIKE', '%' . $query . '%')->paginate(7);      
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

    public function move(Request $request,Record $record)
    {
        # code...
        dd($request);
        $this->validate($request,[

        ]);
    }
}
