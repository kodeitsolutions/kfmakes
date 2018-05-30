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
        $products = Product::join('types','products.type_id','=','types.id')->orderBy('types.name')->orderBy('products.name')->select('products.*')->get(); 
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

    public function components($id)
    {
        //
        $product = Product::find($id);

        $components = $product->components()->get();  
        if (is_null($components))
        {
            return redirect('/product');
        }
        //dd($components);
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
        //
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

    public function cost(Product $product)
    {
        $total = 0;

        $components = $product->components()->get();

        foreach ($components as $component) {
            $total = $total + ($component->cost * $component->pivot->quantity);
        }

        $product->cost_KFD = ($total * 1.5);
        $product->cost_EKF = ($total * 2);
        $product->update();
    }

    public function search(Request $request)
    {
        if ($request->has('type')) {
            $products = Product::whereIn('type_id',$request->type)->get();
        } else {
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
        }

        if($products->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            $types = Type::where('kind','Pieza')->orderBy('name')->get();
            return view('products.index', compact('products','types'));
        }
        

            
    }

    /*public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);        
        
        Excel::create('Piezas', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $products = Product::all();

                $arr = array();
                foreach($products as $product) {
                    foreach($product->components()->get() as $component){
                        $data = array($product->id, $product->name, $product->cost_KFD, $product->cost_EKF, $product->created_at,$product->updated_at,$component->id, $component->pivot->quantity);
                        array_push($arr, $data);
                    }
                }

                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                        'id', 'name', 'cost_KFD', 'cost_EKF', 'created_at','updated_at', 'component_id', 'quantity'
                    )

                );
 
            });
        })->export($request->extension);
        
        return back();
    }*/

    public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);

        Excel::create('Piezas', function($excel) {
 
            $excel->sheet('Piezas', function($sheet) { 

                $products = Product::all();             
 
                $sheet->fromArray($products);
 
            });

            $excel->sheet('Componentes', function($sheet) { 

                $products = Product::all();  

                /*$components = $products->components()->get();

                $sheet->fromArray($components);*/
                $arr = array();
                foreach($products as $product) {
                    foreach($product->components()->get() as $component){
                        $data = array($component->pivot->id, $product->id, $component->id, $component->pivot->quantity);
                        array_push($arr, $data);
                    }
                }

                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                        'id', 'component_id', 'product_id','quantity')

                );
 
            });
        })->export($request->extension);

        return back();
    }

    /*public function import(Request $request)
    {
        # code...
        $this->validate($request, [
            'file' => 'file'
        ]);

        $path = $request->file('products_file')->getRealPath();
        $data = Excel::load($path, function($reader) {})->get();

        $products_sheet = Excel::selectSheetsByIndex(0)->load($path, function($reader) {})->get();
        $components_sheet = Excel::selectSheetsByIndex(0)->load($path, function($reader) {})->get();

        
        
        $count = 0;

        if($request->hasFile('products_file')){
            if(!empty($data) && $data->count()){
                $products = Product::all();
                                
                foreach ($data as $row) {
                    if (!$components->contains('id',$row->id)) {
                        Product::create([
                            'name' => $row->name,
                            'cost_KFD' =>$row->cost_KFD,
                            'cost_EKF' =>$row->cost_EKF,
                            'user_id' =>$row->user_id,
                        ]);
                        $count++;
                    }                    
                }
                
            }

            if(!empty($products_sheet) && $products_sheet->count()){
                $products = Product::all();
                                
                foreach ($products_sheet as $row) {
                    if (!$products->contains('id',$row->id)) {
                        Product::create([
                            'name' => $row->name,
                            'cost_KFD' => $row->cost_KFD,
                            'cost_EKF' => $row->cost_EKF,
                            'user_id' => $row->user_id,
                            'type_id' => $row->type_id, 
                        ]);

                        $product = Product::create($row->toArray());
                        $count++;
                    }                                    
                }
                
            }
        } else {
            $request->session()->flash('flash_message_not', 'No se cargó ningún archivo.');
        }

        if ($count > 0) {
            $request->session()->flash('flash_message', 'Se importaron '.$count.' registros correctamente.');
        } else {
            $request->session()->flash('flash_message_info', 'No habían registros por importar.');
        }
        
        return back();      
    }*/
}
