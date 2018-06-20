<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('module');
    }

    public function module($value='')
    {
        # code...
        return view('layouts.module');
    }

    public function chosen(Request $request)
    {
        # code...
        $this->validate($request,[
            'module' => 'required'
        ]);
        //dd($request);
        if ($request->module == 'costs') {
            return redirect('product');
        } else {
            return redirect('category');
        }
        
    }
}
