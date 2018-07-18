@extends('layouts.app')

@section('content')
	<div class="row">
        <div class="col-md-6 offset-md-3">
        	@if($errors->any())
		        <div class="alert alert-danger">
		          <strong>El formulario tiene un error</strong>
		          @foreach ($errors->all() as $error)
		            <li>{{ $error }}</li>
		          @endforeach
		        </div>
		    @endif
            <div class="card ">
                <div class="card-header">
                    <p class="text-white font-weight-bold"><b>Seleccionar módulo</b></p>
                </div>
                <div class="card-body justify-content-md-center">
                    <form class="form-horizontal" method="POST" action="/chosen">
                    	{{ csrf_field() }}                   	
						  
                        <div class="form-group">
                            <select  id="module" class="form-control input-sm" name="module">
                                <option value="" selected disabled>Seleccionar</option>
                                <option value="costs">Costos</option>
                                <option value="logistics">Logística</option>
                            </select> 
                        </div>
                        <div class="form-group" align="right">
                            <button type="submit" class="btn btn-default ">
                                Aceptar
                            </button>
                        </div>                    
                	</form>
                </div>
            </div>
        </div>
    </div>
@endsection