<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Response;
use App\User;
use App\Type;
use App\Component;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderBy('name')->paginate(7);
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return redirect('/register');
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
        $user = User::find($id);
        if (is_null($user))
        {
            return redirect('/user');;
        }
        return Response::json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        //dd($request);
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        $data = $request->all();
        
        $saved = $user->update($data);

        if ($saved) {
            $request->session()->flash('flash_message', 'Usuario '.$user->name.' editado.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo editar el Usuario.');
        }

        return redirect('user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        //
        $types = Type::where('user_id',$user->id)->get();
        $components = Component::where('user_id',$user->id)->get();        
        $products = Product::where('user_id', $user->id)->get();

        $authenticated = Auth::id();
        if ($authenticated != $user->id and $types->isEmpty() and $components->isEmpty() and $products->isEmpty()) {
            $deleted = $user->delete();

            if ($deleted) {
                $request->session()->flash('flash_message', 'Usuario '.$user->name.' eliminado.');
            }
            else {
                $request->session()->flash('flash_message_not', 'No se pudo eliminar el usuario.');
            }
        } 
        else {
            $request->session()->flash('flash_message_not', 'No se puede eliminar a sí mismo o a un usuario que tenga registros asociados.');
        }
        
        return redirect('user');
    }

    public function search(Request $request)
    {
        $parameter = $request->search;
        $query = $request->value;

        if ($parameter == '' && $query == '') {
            $users = User::orderBy('name')->paginate(7);
        } 
        elseif ($parameter == '' && $query != '') {
            $users = User::where('name','LIKE', $query . '%')
                ->orWhere('email','LIKE', $query . '%')
                ->orderBy('name')
                ->paginate(7);
        }
        else {
            $users = User::where($parameter, 'LIKE', '%' . $query . '%')->orderBy('name')->paginate(7);
        }
        
        if($users->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            return view('users.index', compact('users'));
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        //dd($request);
        $this->validate($request,[
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $password = bcrypt($request->password);
        $user->password = $password;

        $saved = $user->save();

        if ($saved) {
            $request->session()->flash('flash_message', 'Contraseña modificada.');
        }
        else {
            $request->session()->flash('flash_message_not', 'No se pudo modificar la contraseña.');
        }

        return redirect('user');
    }
    public function export(Request $request)
    {
        $this->validate($request, [
            'extension' => 'required',
        ]);

        Excel::create('Usuarios', function($excel) {
 
            $excel->sheet('Datos', function($sheet) { 

                $users = User::all();             
 
                $sheet->fromArray($users);
 
            });
        })->export($request->extension);
        
        return back();
    }

    public function import(Request $request)
    {
        # code...
        $this->validate($request, [
            'file' => 'file'
        ]);            
        
        $count = 0;

        if($request->hasFile('users_file')){
            
            $path = $request->file('users_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            if(!empty($data) && $data->count()){
                $users = User::all();
                                
                foreach ($data as $row) {
                    if (!$users->contains('id',$row->id)) {
                       User::create([
                            'name' => $row->name,
                            'email' =>$row->email,
                            'password' =>bcrypt('exclusivo'),
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
