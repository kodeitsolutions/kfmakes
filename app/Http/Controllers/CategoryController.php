<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Response;
use App\Article;
use App\Category;
use App\Component;
use App\Type;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderBy('name')->paginate(7);
        return view('categories.index',compact('categories'));
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
            'name' => 'required|max:191|unique:categories',
        ]);
        
        $category = new Category($request->all());    

        $category->user_id = Auth::id();
        $saved = $category->save();

        if ($saved) {
            $request->session()->flash('flash_message', 'Categoría '.$category->name.' creada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo crear la categoría.');
        }
        
        return redirect('category');
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
        $category = Category::find($id);
        if (is_null($category)) {
            return redirect('/category');
        }
        return Response::json($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
        $this->validate($request, [
            'name' => 'required|max:191|unique:categories,name,'.$category->id
        ]);

        $saved = $category->update($request->all());

        if ($saved) {
            $request->session()->flash('flash_message', 'Categoría '.$category->name.' modificada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar la categoría.');
        }

        return redirect('/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Category $category)
    {
        //
        if ($category->id == 1 or $category->id == 2) {
            $request->session()->flash('flash_message_not', 'No se puede eliminar la categoría '.$category->name.'.');
        }
        else{  
            $components = Component::where('category_id',$category->id)->get();
            $types = Type::where('category_id',$category->id)->get();        
            $articles = Article::where('category_id',$category->id)->get();

            if ($articles->isEmpty() and $types->isEmpty() and $components->isEmpty()) {  
                $deleted = $category->delete();
                if ($deleted) {
                    $request->session()->flash('flash_message', 'Categoría '.$category->name.' eliminada.');
                }
                else{
                    $request->session()->flash('flash_message_not', 'No se pudo eliminar la categoría.');   
                }
            }
            else {
                $request->session()->flash('flash_message_not', 'No se pudo eliminar la categoría '.$category->id.' ya que existen registros asociados a esta.');
            }
        }

        return redirect('/category');
    }

    public function search(Request $request)
    {        
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $categories = Category::orderBy('name')->paginate(7);
        } 
        elseif ($parameter == '' && $query != '') {
            $categories = Category::Where('name','LIKE', $query . '%')
                ->orderBy('name')
                ->paginate(7);
        } 
        else {
            $categories = Category::where($parameter, 'LIKE', '%' . $query . '%')->orderBy('name')->paginate(7);   
        }

        if($categories->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            return view('categories.index', compact('categories'));
        }            
    }

    public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);

        Excel::create('Categorías', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $categories = Category::all();             
 
                $sheet->fromArray($categories);
 
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

        if($request->hasFile('categories_file')){
            $path = $request->file('categories_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            
            if(!empty($data) && $data->count()){
                $categories = Category::all();
                                
                foreach ($data as $category) {
                    if (!$categories->contains('id',$category->id)) {
                       Category::create([
                            'name' => $category->name,
                            'user_id' =>$category->user_id,
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
