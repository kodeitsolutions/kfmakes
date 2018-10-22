<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Response;
use App\Type;
use App\Category;
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
        $categories = Category::where('name','LIKE', 'Pieza%')
            ->orWhere('name','LIKE','Componente%')
            ->get();
        $types = Type::orderBy('kind')->orderBy('name')->paginate();
        return view('types.index',compact('types','categories'));
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
        //dd($request);
        $this->validate($request, [
            'name' => 'required|max:191|unique:types,name,NULL,id,kind,'.$request->kind,
            'category_id' => 'required'
        ]);
        
        $type = new Type($request->all()); 

        $category = Category::find($request->category_id);
        $type->kind = $category->name;

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
     * @param  int  $id
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
            'category_id' => 'required',
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

        if ($components->isEmpty() and $products->isEmpty()) {
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

    /**
     * Search the specified resource(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {   
        //dd($request);   
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' and $query == '') {
            $types = Type::orderBy('kind')->orderBy('name')->paginate();
        } 
        elseif ($parameter == '' and $query != '') {
            $types = Type::where('kind','LIKE', $query . '%')
                ->orWhere('name','LIKE', $query . '%')
                ->orderBy('kind')->orderBy('name')
                ->paginate();
        } 
        else {
            $types = Type::where($parameter, 'LIKE', '%' . $query . '%')->orderBy('kind')->orderBy('name')->paginate();  
        }

        if($types->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $categories = Category::where('name','LIKE', 'Pieza%')
                ->orWhere('name','LIKE','Componente%')
                ->get();
            return view('types.index', compact('types','categories'));
        }            
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

        Excel::create('Tipos', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $types = Type::all();             
 
                $sheet->fromArray($types);
 
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
        # code...
        $this->validate($request, [
            'file' => 'file'
        ]);            
        
        $count = 0;

        if($request->hasFile('types_file')){
            $path = $request->file('types_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            if(!empty($data) and $data->count()){
                $types = Type::all();
                                
                foreach ($data as $type) {
                    if (!$types->contains('id',$type->id)) {
                       Type::create([
                            'name' => $type->name,
                            'kind' =>$type->kind,
                            'user_id' =>$type->user_id,
                        ]);
                       $count++;
                    }                    
                }
            }
            if ($count > 0) {
                $request->session()->flash('flash_message', 'Se importaron '.$count.' registros correctamente.');
            } else {
                $request->session()->flash('flash_message_info', 'No habían registros por importar.');
            }
        } else {
            $request->session()->flash('flash_message_not', 'No se cargó ningún archivo.');
        }
        
        return back();      
    }
}