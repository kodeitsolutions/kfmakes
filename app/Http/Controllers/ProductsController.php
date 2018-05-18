<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Type;
use App\Product;
use App\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->get(); 
        return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $type_products = Type::where('kind','Pieza')->orderBy('name')->get();
        $type_components = Type::where('kind','Componente')->orderBy('name')->get();
        $components = Component::orderBy('name')->get();
        $product = new Product();
        return view('products.create',compact('product','type_products','type_components','components'))->with("route","add");
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
            'type_id' => 'required',
            'name' => 'required|max:191',
        ]);

        $product = new Product();

        $product->type_id = $request->type_id;
        $product->name = $request->name;
        $product->user_id = Auth::id();

        $saved = $product->save();

        foreach ($request->except(['_method','_token','type_id','name']) as $key => $value) {
            if (!is_null($value)) {
                $product->components()->attach($key,['quantity' => $value]) ;
            }
        }

        $this->cost($product);

        if ($product->cost_EKF == 0 && $product->cost_KFD == 0) {
            $product->delete();
            $request->session()->flash('flash_message_not', 'No se pudo agregar la Pieza. Debe indicar al menos un componente');
        } else {
            if ($saved) {
            $request->session()->flash('flash_message', 'Pieza agregada.');
            }
            else {
                $request->session()->flash('flash_message_not', 'No se pudo agregar la Pieza.');
            }
        }       

        return redirect('product');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::find($id);
        if (is_null($product))
        {
            return redirect('/product');
        }
        return Response::json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Product $product)
    {
        //
        $type_products = Type::where('kind','Pieza')->orderBy('name')->get();
        $type_components = Type::where('kind','Componente')->orderBy('name')->get();
        $components = $product->components()->get();

        return view('products.create',compact('product','type_products','type_components','components'))->with("route","edit");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        //dd($request);
        $this->validate($request, [
            'type_id' => 'required',
            'name' => 'required|max:191',
        ]);
        //dd($request);

        $product->type_id = $request->type_id;
        $product->name = $request->name;

        $saved = $product->update();

        $components = $product->components()->get();
        //dd($components);

        foreach ($request->except(['_method','_token','type_id','name']) as $key => $value) {
            if($components->contains('id',$key)){
                if (is_null($value)) {
                    $product->components()->detach($key,['quantity' => $value]) ;
                }
                else {
                    $product->components()->updateExistingPivot($key,['quantity' => $value]);
                }
            }
            else {
                if (!is_null($value)) {
                    $product->components()->attach($key,['quantity' => $value]) ;
                }
            }
        }

        $this->cost($product);

        if ($saved) {
            $request->session()->flash('flash_message', 'Pieza modificada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar la Pieza.');
        }

        return redirect('product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $product->components()->detach();
        
        $deleted = $product->delete();
        if ($deleted) {
            return back()->with('flash_message', 'Pieza eliminada.');
        }
        else{
            return back()->with('flash_message_not', 'No se pudo eliminar la Pieza.');   
        }
    }

    public function cost(Product $product)
    {
        $total = 0;

        $components = $product->components()->get();

        foreach ($components as $component) {
            $total = $total + ($component->cost * $component->pivot->quantity);
        }

        $product->cost_KFD = ($total * 2);
        $product->cost_EKF = ($total * 1.5);
        $product->update();
    }

    public function search(Request $request)
    {
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->get(); ;
        } 
        elseif ($parameter == '' && $query != '') {
            $products = Product::where('products.name','LIKE', $query . '%')
                ->orWhere('cost_EKF','LIKE', $query . '%')
                ->orWhere('cost_KFD','LIKE', $query . '%')
                ->orWhereHas('type', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')
                ->get(); 
        }
        elseif ($parameter == 'type') {
            $products = Product::whereHas('type', function ($q) use ($query){
                    $q->where('name','LIKE', '%' . $query . '%');
                })
            ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')
            ->get(); 
        }
        elseif ($parameter == 'cost') {
            $products = Product::where('cost_EKF','LIKE', $query . '%')
                ->orWhere('cost_KFD','LIKE', $query . '%')
                ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')
                ->get(); 
        } else {
            $products = Product::where('products.'.$parameter, 'LIKE', '%' . $query . '%')
                ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->get(); 
        }        
        
        if($products->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $types = Type::all();
            return view('products.index', compact('products','types'));
        }
    }
}
