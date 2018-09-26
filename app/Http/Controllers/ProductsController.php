<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
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
        $types = Type::where('kind','Pieza')->orderBy('name')->get();
        $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->paginate(7); 
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
        $type_products = Type::where('kind','Pieza')->orderBy('name')->get();
        $type_components = Type::join('components','components.type_id','=','types.id')->select('types.*')->where('kind','Componente')->orderBy('types.name')->distinct()->get();
        $product = new Product();
        $components = Component::orderBy('name')->get();
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
     * @param  int  $id
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
        $product->append('type_name')->toArray();
        return Response::json($product);
    }

    public function components($id)
    {
        //
        $product = Product::find($id);

        $components = $product->components()->get();  

        if (is_null($components))
        {
            return redirect('/product');
        }
        foreach ($components as $component) {
            $component->append('type_name')->toArray();
        }
        return Response::json($components);
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
        $type_components = $type_components = Type::join('components','components.type_id','=','types.id')->select('types.*')->where('kind','Componente')->orderBy('types.name')->distinct()->get();
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
        //dd($request);
        $this->validate($request, [
            'type_id' => 'required',
            'name' => 'required|max:191',
        ]);

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

    /**
     * Calculate cost of products based on its components
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function cost(Product $product)
    {
        $total = 0;

        $components = $product->components()->get();

        foreach ($components as $component) {
            $total = $total + ($component->cost * $component->pivot->quantity);
        }

        $product->cost_KFD = number_format(($total * 1.5),2);
        $product->cost_EKF = number_format(($total * 2),2);
        $product->update();
    }

    /**
     * Recalculate cost of products based on its components, in case the components change of price.
     *
     * @return \Illuminate\Http\Response
     */
    public function recalculate()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $this->cost($product);
        }

        return redirect('product');
    }

    /**
     * Search the specified resource(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if ($request->has('type')) {
            $products = Product::whereIn('type_id',$request->type)->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->paginate(7);
        } else {
            $parameter = $request->search;
            $query = $request->value;

            if ($parameter == '' && $query == '') {
                $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->paginate(7);
            } 
            elseif ($parameter == '' && $query != '') {
                $products = Product::where('products.name','LIKE', $query . '%')
                    ->orWhere('cost_EKF','LIKE', $query . '%')
                    ->orWhere('cost_KFD','LIKE', $query . '%')
                    ->orWhereHas('type', function ($q) use ($query){
                            $q->where('name','LIKE', '%' . $query . '%');
                        })
                    ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')
                    ->paginate(7); 
            }
            elseif ($parameter == 'type') {
                $products = Product::whereHas('type', function ($q) use ($query){
                        $q->where('name','LIKE', '%' . $query . '%');
                    })
                ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')
                ->paginate(7);; 
            }
            elseif ($parameter == 'cost') {
                $products = Product::where('cost_EKF','LIKE', $query . '%')
                    ->orWhere('cost_KFD','LIKE', $query . '%')
                    ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')
                    ->paginate(7); 
            } else {
                $products = Product::where('products.'.$parameter, 'LIKE', '%' . $query . '%')
                    ->join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->paginate(7);
            }             
        }

        if($products->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $types = Type::where('kind','Pieza')->orderBy('name')->get();
            return view('products.index', compact('products','types'));
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

        Excel::create('Piezas', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $products = Product::with('type')->get();
                $components = Component::with('type')->join('types','components.type_id','=','types.id')->orderBy('types.name')->orderBy('components.name')->select('components.*')->get();

                for ($i=0; $i < count($products) ; $i++) { 
                    # code...
                    $productData[] = [
                        'ID' => $products[$i]->id,
                        'NOMBRE' => $products[$i]->name,
                        'TIPO' => $products[$i]->type->name,
                        'COSTO KFD' => $products[$i]->cost_KFD,
                        'COSTO EKF' => $products[$i]->cost_EKF,                
                    ];

                    //ADD ALL COMPONENTS - AGREGAR TODOS LOS COMPONENTES
                    foreach ($components as $component) {
                        $productData[$i][$component->type->name.'/'.$component->name] = '0.000';
                    }
                    //SET VALUE OF PRODUCT COMPONENTS - ASIGNAR EL VALOR DE LOS COMPONENTES DE LA PIEZA
                    foreach($products[$i]->components()->get() as $component){
                        $productData[$i][$component->type->name.'/'.$component->name] = $component->pivot->quantity;
                    }
                }     
 
                $sheet->fromArray($productData);
 
            });

        })->export($request->extension);
    }    
}
