<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Type;
use App\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComponentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $types = Type::where('kind','Componente')->get();
        $components = Component::all();
        return view('components.index',compact('components','types'));
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
        $this->validate($request, [
            'type_id' => 'required',
            'name' => 'required|max:191|unique:components,deleted_at,NULL',
            'cost' => 'required'
        ]);

        $component = new Component($request->all());       

        $component->user_id = Auth::id();
        $saved = $component->save();

        if ($saved) {
            $request->session()->flash('flash_message', 'Componente '.$component->type->name.' / '.$component->name.' creado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo crear el componente.');
        }
        
        return redirect('component');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $component = Component::find($id);
        if (is_null($component))
        {
            return redirect('/component');
        }
        //dd($component);
        return Response::json($component);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        //
        $this->validate($request, [
            'type_id' => 'required',
            'name' => ['required','max:191',Rule::unique('components')->ignore($component->id)],
            'cost' => 'required'
        ]);

        $data = $request->all();        
        
        $saved = $component->update($data);

        if ($saved) {
            $request->session()->flash('flash_message', 'Componente '.$component->type->name.' / '.$component->name.' modificado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar el Componente.');
        }

        return redirect('/component');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Component $component)
    {
        //
        $products = $component->products()->get();
        //dd($products);

        if ($products->isEmpty()) {
            $deleted = $component->delete();
            if ($deleted) {
                $request->session()->flash('flash_message', 'Componente '.$component->name.' eliminado.');
            }
            else{
                $request->session()->flash('flash_message_not', 'No se pudo eliminar el componente.');   
            }
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo eliminar el componente ya que existen piezas que lo contienen.');
        }

        return redirect('/component');
    }

    public function search(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'search' => 'required',
            'value' => 'required'
        ]);

        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == 'type') {
            # code...
            $type = Type::where('name','LIKE', '%' . $query . '%')->first();            
            $components = Component::where('type_id',$type->id)->get();
            //dd($components);
        } else {
            $components = Component::where($parameter, 'LIKE', '%' . $query . '%')->get();
        }        
        
        if($components->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $types = Type::all();
            return view('components.index', compact('components','types'));
        }
    }
}
