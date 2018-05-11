@extends('layouts.app')

@section('content')
    <div class="col-md-6 offset-md-3">    
        <div class="card">
            <div class="card-header">
                <span>Bienvenido</span> 
            </div>
            <div class="card-body">
                <div class="col-3">    
                    <a href="/component/create" class="btn btn-primary btn-lg " role="button" aria-pressed="true">Componentes</a>
                </div>
                <div class="col-3">
                    <a href="/product/create" class="btn btn-primary btn-lg " role="button" aria-pressed="true">Piezas</a>
                </div>             
            </div> 
            <div class="card-footer">Footer</div>
        </div>
    </div>
@endsection
