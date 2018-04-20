<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\User;
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
        $users = User::all();
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
        //
        $this->validate($request, [
            'name-edit' => 'required|max:255',
            'email-edit' => 'required|email',
        ]);

        $data = $request->all();
        
        $data['isAdmin'] = ($request->has('isAdmin')) ? 1 : 0;
        $data['isGuard'] = ($request->has('isGuard')) ? 1 : 0;

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
    public function destroy($id)
    {
        //
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

        $users = User::where($parameter, 'LIKE', '%' . $query . '%')->get();
        
        if($users->isEmpty()) {
            return back()->with('flash_message_info', 'No hay resultados para la búsqueda realizada.');
        }
        else {
            return view('users.index', compact('users'));
        }
    }
}
