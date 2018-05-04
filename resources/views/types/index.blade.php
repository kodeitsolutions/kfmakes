@extends('layouts.app')

@section('modal-add')
	<div id="myModalAdd" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Agregar Tipo</h4>
		        </div>


				<form method="POST" action="/type/add">
					{{ csrf_field()}}

					<div class="modal-body form-group">  

			            <div class="form-group">
			                <label class="control-label c">Tipo:</label>
			            	<select id="kind" class="form-control input-sm" name="kind" required>
			          			<option value="" selected disabled>Seleccione el tipo:</option>
			          			<option value="Componente">Componente</option>
			          			<option value="Pieza">Pieza</option>
			          		</select>
			            </div>

			            <div class="form-group">
			                <label class="control-label">Nombre:</label>
			                <input type="text" class="form-control" name="name" value="" placeholder="Ingrese el nombre." required autofocus>
			            </div>
			        </div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		              	<button type="submit" class="btn btn-primary btn-edit" id="saveButton">Guardar</button>
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
		            <h4 class="modal-title">Eliminar Tipo</h4>
		        </div>
		        <div class="modal-body">
		          <p>¿Está seguro que desea eliminar el tipo?</p>
		          <label id="name">Nombre</label>
		        </div>
	        	<div class="modal-footer ">           
		          	<form method="POST" action="" id="delete">
			            {{ method_field('DELETE') }}
			            {{ csrf_field() }}
			            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			            <button type="submit" class="btn btn-danger btn-delete">Eliminar</button>
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
		            <h4 class="modal-title">Editar tipo</h4>
		        </div>
	        	
	        	<form method="POST" action="" id="edit">
		          	{{ method_field('PATCH') }}
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label class="control-label">Tipo:</label>
			                <select id="kind" class="form-control input-sm" name="kind">
			          			<option value="Componente">Componente</option>
			          			<option value="Pieza">Pieza</option>
			          		</select>                 
			            </div>  

			            <div class="form-group">
		                	<label class="control-label">Nombre:</label>
		                	<input type="text" class="form-control" name="name" id="name" value="" autofocus>
		              	</div>           
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		              	<button type="submit" class="btn btn-primary btn-edit" id="saveButton">Guardar</button>
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
	        	
	        	<form method="GET" action="/type/search">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search" class="form-control input-sm" name="search">
			            		<option value="0" selected disabled>Seleccione el parámetro de búsqueda</option>
			            		<option value="kind">Tipo</option>
			            		<option value="name">Nombre</option>
			            	</select>                 
			            </div>  

			            <div class="form-group">
		                	<input type="text" class="form-control" name="value" value="" placeholder="Buscar...">
		              	</div>           
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		              	<button type="submit" class="btn btn-primary btn-edit" id="saveButton">Buscar</button>
		            </div>
		        </form>
		          
	      	</div>      
	    </div> 
  	</div>
@endsection

@section('content')
	<div class="col-md-12 row">
	    @if($errors->any())
	        <div class="alert alert-danger">
	          	<strong>El formulario tiene un error</strong>
		        @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </div>
	    @endif
	    
	</div>

	<div class="col-md-12 row button-table" align="right">        	
		<button class="btn btn-success" data-toggle="modal" data-target="#myModalAdd" role="button"><span class="glyphicon glyphicon-plus"></span> Agregar </button>
		<button class="btn btn-primary" data-toggle="modal" data-target="#myModalSearch" role="button"><span class="glyphicon glyphicon-search"></span> Buscar </button>
	</div>
	<div class="row col-md-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Tipo</th>
					<th>Nombre</th>
					<th colspan="2" class="centered">Operación</th>
				</tr>
			</thead>
			<tbody>
				@foreach($types as $type)
					<tr>
						<td>{{ $type->kind }}</td>
						<td>{{ $type->name }}</td>
						
						<td align="right"><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModalEdit" data-id="{{$type->id}}"><span class="glyphicon glyphicon-pencil"></span></button></td>
              			<td><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModalDelete" data-id="{{$type->id}}"><span class="glyphicon glyphicon-trash"></span></button></td>
						
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection

@section('script')
  <script type="text/javascript"> 
    $('#myModalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // BOTÓN QUE EJECUTÓ EL MODAL
        var type_id = button.data('id')

        modalDelete("type", type_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var type_id = button.data('id');

       modalEdit("type",type_id);
    });       
  </script>
@endsection