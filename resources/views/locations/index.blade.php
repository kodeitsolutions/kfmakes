@extends('layouts.app')

@section('modal-add')
	<div id="myModalAdd" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Agregar Ubicación</h4>
		        </div>

				<form method="POST" action="/location/add">
					{{ csrf_field()}}

					<div class="modal-body form-group">  

			            <div class="form-group">
			                <label class="control-label">Nombre:</label>
			                <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" value="" placeholder="Ingrese el nombre." required autofocus>
			                @if ($errors->has('name'))
				                <div class="invalid-feedback">
	                                <span><strong>{{ $errors->first('name') }}</strong></span>
	                            </div>
	                        @endif
			            </div>

			            <div class="form-group">
			                <label class="control-label">Teléfono:</label>
			                <input type="text" class="form-control @if ($errors->has('telephone')) is-invalid @endif" name="telephone" value="" placeholder="Ingrese el teléfono.">
			                @if ($errors->has('telephone'))
				                <div class="invalid-feedback">
	                                <span><strong>{{ $errors->first('telephone') }}</strong></span>
	                            </div>
	                        @endif
			            </div>

			            <div class="form-group">
			                <label class="control-label">Encargado:</label>
			                <input type="text" class="form-control" name="in_charge" value="" placeholder="Ingrese el nombre del encargado.">
			            </div>

			            <div class="form-group">
			                <label class="control-label">País:</label>
			                <select  id="country" class="form-control input-sm @if ($errors->has('country')) is-invalid @endif" name="country" required>
			            		<option value="" selected disabled>Seleccione el país</option>
			            		<option value="Estados Unidos">Estados Unidos</option>
			            		<option value="Venezuela">Venezuela</option>
			            	</select> 
			            	@if ($errors->has('country'))
				                <div class="invalid-feedback">
	                                <span><strong>{{ $errors->first('country') }}</strong></span>
	                            </div>
	                        @endif
			            </div>
			        </div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default">Guardar</button>
		            </div>
				</form>		        
	      	</div>	      	   
	    </div>    
	</div>
@endsection

@section('modal-delete')
  	<div id="myModalDelete" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Eliminar Ubicación</h4>
		        </div>
		        <div class="modal-body">
		          <p>¿Está seguro que desea eliminar la ubicación?</p>
		          <label id="name">Nombre</label>
		        </div>
	        	<div class="modal-footer ">           
		          	<form method="POST" action="" id="delete">
			            {{ method_field('DELETE') }}
			            {{ csrf_field() }}
			            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			            <button type="submit" class="btn btn-default">Eliminar</button>
		          	</form>
	        	</div>
	      	</div>      
	    </div>    
	</div>
@endsection

@section('modal-edit')
  	<div id="myModalEdit" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Editar ubicación</h4>
		        </div>
	        	
	        	<form method="POST" action="" id="edit">
		          	{{ method_field('PATCH') }}
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		                <div class="form-group">
		                	<label class="control-label">Nombre:</label>
		                	<input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" name="name" id="name" value="" required autofocus>
		                	@if ($errors->has('name'))
				                <div class="invalid-feedback">
	                                <span><strong>{{ $errors->first('name') }}</strong></span>
	                            </div>
	                        @endif
		              	</div>           

		              	<div class="form-group">
		                	<label class="control-label">Teléfono:</label>
		                	<input type="text" class="form-control @if ($errors->has('telephone')) is-invalid @endif" name="telephone" id="telephone" value="">
		                	@if ($errors->has('telephone'))
				                <div class="invalid-feedback">
	                                <span><strong>{{ $errors->first('telephone') }}</strong></span>
	                            </div>
	                        @endif
		              	</div>

		              	<div class="form-group">
		                	<label class="control-label">Encargado:</label>
		                	<input type="text" class="form-control" name="in_charge" id="in_charge" value="">
		              	</div>
		              	<div class="form-group">
			                <label class="control-label">País:</label>
			                <select  id="country" class="form-control input-sm @if ($errors->has('country')) is-invalid @endif" name="country">
			            		<option value="Estados Unidos">Estados Unidos</option>
			            		<option value="Venezuela">Venezuela</option>
			            	</select> 
			            	@if ($errors->has('country'))
				                <div class="invalid-feedback">
	                                <span><strong>{{ $errors->first('country') }}</strong></span>
	                            </div>
	                        @endif
			            </div>
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default">Guardar</button>
		            </div>
		        </form>
		          
	      	</div>      
	    </div> 
  	</div>
@endsection

@section('modal-search')
	<div id="myModalSearch" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Buscar tipo</h4>
		        </div>
	        	
	        	<form method="GET" action="/location/search">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search" class="form-control input-sm" name="search">
			            		<option value="" selected disabled>Seleccione el parámetro de búsqueda</option>
			            		<option value="name">Nombre</option>
			            		<option value="telephone">Teléfono</option>
			            		<option value="in_charge">Encargado</option>
			            		<option value="country">País</option>
			            	</select>                 
			            </div>  

			            <div class="form-group">
		                	<input type="text" class="form-control" name="value" value="" placeholder="Buscar...">
		              	</div>           
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default">Buscar</button>
		            </div>
		        </form>
		          
	      	</div>      
	    </div> 
  	</div>
@endsection

@section('modal-import')
	<div id="myModalImport" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Importar datos</h4>
		        </div>
	        	
	        	<form method="POST" action="/location/import" enctype="multipart/form-data">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">
			            <div class="form-group">
			            	<label>Cargar archivo:</label>
		                	<input type="file" class="form-control-file" name="locations_file" id="file">
		              	</div>           
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default">Importar</button>
		            </div>
		        </form>
		          
	      	</div>      
	    </div> 
  	</div>
@endsection

@section('modal-export')
	<div id="myModalExport" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Exportar datos</h4>
		        </div>
	        	
	        	<form method="GET" action="/location/export">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Formato de archivo:</label>
            				<select  id="extension" class="form-control input-sm" name="extension">
			            		<option value="" selected disabled>Seleccione el formato</option>
			            		<option value="xls">XLS</option>
			            		<option value="xlsx">XLSX</option>
			            		<option value="csv">CSV</option>
			            	</select>                 
			            </div> 			                   
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default" id="export">Exportar</button>
		            </div>
		        </form>
		          
	      	</div>      
	    </div> 
  	</div>
@endsection

@section('navigation')
  @if (Auth::check())
    @include('layouts.logistics')
  @endif
@endsection

@section('content')
	{{--<div class="col-md-12 row">
	    @if($errors->any())
	        <div class="alert alert-danger">
	          	<strong>El formulario tiene un error</strong>
		        @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </div>
	    @endif	    
	</div>--}}

	<div class="row"> 
		<div class="mr-auto ml-3">
			<span data-toggle="modal" data-target="#myModalImport"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Importar"><span class="fa fa-upload"></span></button></span>
			<span data-toggle="modal" data-target="#myModalExport"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Exportar"><span class="fa fa-download"></span></button></span>
		</div>
		<div class="ml-auto">
			<span data-toggle="modal" data-target="#myModalAdd"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Agregar"><span class="fa fa-plus"></span></button></span>
			<span data-toggle="modal" data-target="#myModalSearch"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Buscar"><span class="fa fa-search"></span></button></span>
		</div>	
	</div>
	
	<div class="table-responsive-sm">
		<table class="table table-hover">
			<thead class="thead-index">
				<tr class="text-white">
					<th scope="col">Nombre</th>
					<th scope="col">Teléfono</th>
					<th scope="col">Encargado</th>
					<th scope="col">País</th>
					<th scope="col" colspan="2" class="text-center">Operación</th>
				</tr>
			</thead>
			<tbody>
				@foreach($locations as $location)
					<tr>
						<td>{{ $location->name }}</td>
						<td>{{ $location->telephone }}</td>
						<td>{{ $location->in_charge}}</td>
						<td>{{ $location->country}}</td>
						<td align="right"><span data-toggle="tooltip" data-placement="top" title="Editar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEdit" data-id="{{$location->id}}"><span class="fa fa-pencil"></span></button></span></td>
	          			<td><span data-toggle="tooltip" data-placement="top" title="Eliminar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$location->id}}"><span class="fa fa-trash"></span></button></span></td>
						
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row justify-content-center">
		{{ $locations->appends(Request::except('page'))->render('vendor.pagination.bootstrap-4') }}
	</div>
@endsection

@section('script')
  <script> 
    $('#myModalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // BOTÓN QUE EJECUTÓ EL MODAL
        var location_id = button.data('id');

        modalDelete("location", location_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);         

        location_id = (button.data('id') == null) ? getModalId(localStorage.getItem('modal')) : button.data('id');

        clearErrors('#myModalEdit_' + location_id);
       	modalEdit("location",location_id);
    });  

    $('#myModalAdd').on('shown.bs.modal', function (event) {
    	clearErrors('#myModalAdd');  
    	localStorage.setItem('modal', '#myModalAdd');
    });
    $('#myModalEdit').on('shown.bs.modal', function (event) {
        var location_id = $(event.relatedTarget).data('id');  
    	localStorage.setItem('modal', '#myModalEdit_' + location_id);
    });

    @if (count($errors) > 0)
    	var modal = getModalShow();
    	$(modal).modal('show');
	@endif   
  </script>
@endsection