<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Type;
use App\Component;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $types = Type::orderBy('kind')->orderBy('name')->get();
        return view('types.index',compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('types.create');
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
        $this->validate($request, [
            'name' => 'required|max:191|unique:types,name,NULL,id,kind,'.$request->kind,
            'kind' => 'required|max:191'
        ]);

        
        $type = new Type($request->all());       

        $type->user_id = Auth::id();
        $saved = $type->save();

        if ($saved) {
            $request->session()->flash('flash_message', 'Tipo '.$type->kind.' / '.$type->name.' creado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo crear el tipo.');
        }
        
        return redirect('type');  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $type = Type::find($id);
        if (is_null($type))
        {
            return redirect('/type');
        }
        //dd($type);
        return Response::json($type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //
        $this->validate($request, [
            'name' => 'required|max:191|unique:types,name,'.$type->id.',id,kind,'.$request->kind,
            'kind' => 'required|max:191',
        ]);

        $data = $request->all();        
        
        $saved = $type->update($data);

        if ($saved) {
            $request->session()->flash('flash_message', 'Tipo '.$type->kind.' / '.$type->name.' modificado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar el Tipo.');
        }

        return redirect('/type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Type $type)
    {
        //
        $components = Component::where('type_id',$type->id)->get();
        $products = Product::where('type_id',$type->id)->get();

        if ($components->isEmpty() && $products->isEmpty()) {
            $deleted = $type->delete();
            if ($deleted) {
                $request->session()->flash('flash_message', 'Tipo '.$type->kind.' / '.$type->name.' eliminado.');
            }
            else{
                $request->session()->flash('flash_message_not', 'No se pudo eliminar el tipo.');   
            }
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo eliminar el Tipo ya que existen registros asociados a este.');
        }

        return redirect('/type');
    }

    public function search(Request $request)
    {        
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $types = Type::orderBy('kind')->orderBy('name')->get();
        } 
        elseif ($parameter == '' && $query != '') {
            $types = Type::where('kind','LIKE', $query . '%')
                ->orWhere('name','LIKE', $query . '%')
                ->orderBy('kind')->orderBy('name')
                ->get();
        } 
        else {
            $types = Type::where($parameter, 'LIKE', '%' . $query . '%')->orderBy('kind')->orderBy('name')->get();        
        }

        if($types->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            return view('types.index', compact('types'));
        }            
    }
}
