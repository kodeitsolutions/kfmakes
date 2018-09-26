<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Response;
use App\Article;
use App\Category;
use App\Product;
use App\Record;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderBy('name')->get();
        $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->get();
        $articles = Article::orderBy('name')->paginate(7);
        return view('articles.index',compact('articles','categories','products'));
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
            'name' => 'required|max:191|unique:articles',            
            'category_id' => 'required',
            'product_id' => 'required_if:category_id,2'
        ]);

        $article = new Article($request->all());

        $article->user_id = Auth::id();
        $saved = $article->save();

        if ($saved) {
            $request->session()->flash('flash_message', 'Artículo '.$article->name.' creado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo crear el artículo.');
        }
        
        return redirect('article');
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
        $article = Article::find($id);
        if (is_null($article)) {
            return redirect('/article');
        }
        return Response::json($article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
        $this->validate($request, [
            'name' => 'required|max:191|unique:articles,name,'.$article->id,
            'category_id' => 'required',
            'product_id' => 'required_if:category_id,2'
        ]);

        $saved = $article->update($request->all());

        if ($saved) {
            $request->session()->flash('flash_message', 'Artículo '.$article->name.' modificado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar el artículo.');
        }

        return redirect('/article');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Article $article)
    {
        //
        $records = Record::where('article_id',$article->id)->get();

        if ($records->isEmpty()) {
            $deleted = $article->delete();
            if ($deleted) {
                $request->session()->flash('flash_message', 'Artículo '.$article->name.' eliminado.');
            }
            else{
                $request->session()->flash('flash_message_not', 'No se pudo eliminar el artículo.');   
            }
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo eliminar el artículo ya que existen registros asociados a este.');
        }

        return redirect('/article');
    }

    /**
     * Search the specified resource(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {        
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $articles = Article::orderBy('name')->paginate(7);
        } 
        elseif ($parameter == '' && $query != '') {
            $articles = Article::where('articles.name','LIKE', $query . '%')
                ->orWhereHas('category', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->orWhereHas('product', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->join('categories','articles.category_id','=','categories.id')
                ->leftJoin('products','articles.product_id','=','products.id')
                ->orderBy('articles.name')
                ->select('articles.*')
                ->paginate(7);           
        } 
        elseif ($parameter == 'category') {
            $articles = Article::whereHas('category', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->join('categories','articles.category_id','=','categories.id')
                ->orderBy('articles.name')->select('articles.*')
                ->paginate(7); 
        }
        elseif ($parameter == 'product') {
            $articles = Article::whereHas('product', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->leftJoin('products','articles.product_id','=','products.id')
                ->orderBy('articles.name')->select('articles.*')
                ->paginate(7); 
        }
        else {
            $articles = Article::where($parameter, 'LIKE', '%' . $query . '%')->orderBy('name')->paginate(7);   
        }

        if($articles->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $categories = Category::orderBy('name')->get();
            $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->get();
            return view('articles.index', compact('articles','categories','products'));
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

        Excel::create('Artículos', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $articles = Article::all();             
 
                $sheet->fromArray($articles);
 
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

        if($request->hasFile('articles_file')){
            $path = $request->file('articles_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            
            if(!empty($data) && $data->count()){
                $articles = Article::all();
                                
                foreach ($data as $article) {
                    if (!$articles->contains('id',$article->id)) {
                       Article::create([
                            'name' => $article->name,
                            'category_id' => $article->category_id,
                            'product_id' => $article->product_id,
                            'stock' => $article->stock,
                            'user_id' =>$article->user_id,
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
