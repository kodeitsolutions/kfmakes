@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Bienvenido</span> 
                        <span style="float:right"> Actualizado al 30/04/2018</span>
                    </div>

                    <div class="panel-body">
                        <div class="col-md-6">
                            <img class="img-responsive" src="http://via.placeholder.com/375x300" alt="components">
                            <a href="/component/create" class="btn btn-default btn-dashboard">Componentes</a>
                        </div>
                        <div class="col-md-6">
                            <img class="img-responsive" src="http://via.placeholder.com/375x300" alt="products">
                            <a href="/product/create" class="btn btn-default btn-dashboard">Piezas</a>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
