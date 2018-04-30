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
        $types = Type::all();
        $products = Product::all();
        return view('products.index',compact('products','types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $type_products = Type::where('kind','Pieza')->get();
        $type_components = Type::where('kind','Componente')->get();
        $components = Component::all();
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
        //
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

        if ($saved) {
            $request->session()->flash('flash_message', 'Pieza agregada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo agregar la Pieza.');
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
        $type_products = Type::where('kind','Pieza')->get();
        $type_components = Type::where('kind','Componente')->get();
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
        $this->validate($request, [
            'type_id' => 'required',
            'name' => 'required|max:191',
        ]);
        //dd($request);

        $product->type_id = $request->type_id;
        $product->name = $request->name;

        $saved = $product->update();

        $components = $product->components()->get();

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
                $product->components()->attach($key,['quantity' => $value]) ;
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

        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $products = Product::all();
        } 
        elseif ($parameter == '' && $query != '') {
            $products = Product::where('name','LIKE', $query . '%')
                ->orWhere('cost_EKF','LIKE', $query . '%')
                ->orWhere('cost_KFD','LIKE', $query . '%')
                ->orWhereHas('type', function ($q) use ($query){
                    $q->where('name','LIKE', '%' . $query . '%');
                })->get();
        }
        elseif ($parameter == 'type') {
            $products = Product::whereHas('type', function ($q) use ($query){
                $q->where('name','LIKE', '%' . $query . '%');
            })->get();
        }
        elseif ($parameter == 'cost') {
            $products = Product::where('cost_EKF','LIKE', $query . '%')
                ->orWhere('cost_KFD','LIKE', $query . '%')->get();
        } else {
            $products = Product::where($parameter, 'LIKE', '%' . $query . '%')->get();
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
