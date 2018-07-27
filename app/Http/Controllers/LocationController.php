<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Response;
use App\Location;
use App\Record;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $locations = Location::orderBy('name')->paginate(7);
        return view('locations.index',compact('locations'));
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
        $this->validate($request,[
            'name' => 'required|max:191|unique:locations',
            'telephone' => 'max:11|regex:/^\d{3,4}-\d{7}$/',
            'country' => 'required'
        ]);

        /*if ($request->has('telephone')) {
            $numbersOnly = preg_replace("[^0-9]", "", $request->telephone);
            $numberOfDigits = strlen($numbersOnly);
            if ($numberOfDigits == 7 or $numberOfDigits == 10) {
                echo $numbersOnly;
            } else {
                return back()->with('flash_message_not', 'No se pudo crear la ubicación.');
            }
        }*/
        

        $location = new Location($request->all());

        $location->user_id = Auth::id();
        $saved = $location->save();

        if ($saved) {
            $request->session()->flash('flash_message', 'Ubicación '.$location->name.' creada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo crear la ubicación.');
        }
        
        return redirect('location');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $location = Location::find($id);
        if (is_null($location)) {
            return redirect('/location');
        }
        return Response::json($location);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        //
        $this->validate($request, [
            'name' => 'required|max:191|unique:locations,name,'.$location->id,
            'telephone' => 'max:11|regex:/^\d{3,4}-\d{7}$/',
            'country' => 'required'
        ]);

        $saved = $location->update($request->all());

        if ($saved) {
            $request->session()->flash('flash_message', 'Ubicación '.$location->name.' modificada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar la ubicación.');
        }

        return redirect('/location');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Location $location)
    {
        //
        $records = Record::where('location_id',$location->id)->get();

        if ($records->isEmpty()) {
            $deleted = $location->delete();
            if ($deleted) {
                $request->session()->flash('flash_message', 'Ubicación '.$location->name.' eliminada.');
            }
            else{
                $request->session()->flash('flash_message_not', 'No se pudo eliminar la ubicación.'); 
            }
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo eliminar la ubicación ya que existen registros asociados a esta.');
        }

        return redirect('/location');
    }

    public function search(Request $request)
    {        
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $locations = Location::orderBy('name')->paginate(7);
        } 
        elseif ($parameter == '' && $query != '') {
            $locations = Location::where('name','LIKE', $query . '%')
                ->orWhere('telephone','LIKE', $query . '%')
                ->orWhere('in_charge','LIKE', $query . '%')
                ->orWhere('country','LIKE', $query . '%')
                ->orderBy('name')
                ->paginate(7);
        } 
        else {
            $locations = Location::where($parameter, 'LIKE', '%' . $query . '%')->orderBy('name')->paginate(7);   
        }

        if($locations->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {            
            return view('locations.index', compact('locations','categories','products'));
        }            
    }

    public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);

        Excel::create('Ubicaciones', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $locations = Location::all();             
 
                $sheet->fromArray($locations);
 
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
}
