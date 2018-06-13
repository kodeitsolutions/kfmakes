@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
               <div class="card-header">
                    <p class="text-white font-weight-bold"><span class="fa fa-times text-white font-weight-bold"></span><b> Error</b></p>
                </div>
              <div class="card-body text-center">
                <h5 class="card-title ">Lo sentimos. Ha ocurrido un error.</h5>
                <a href="javascript:history.go(-1)" class="btn btn-default" role="button"><span class="fa fa-arrow-left"></span>  Regresar</a>
              </div>
            </div>
        </div>
    </div>
@endsection
