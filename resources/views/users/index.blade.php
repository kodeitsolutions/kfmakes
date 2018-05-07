@extends('layouts.app')

@section('modal-add')
	<div id="myModalAdd" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Agregar Usuario</h4>
		        </div>

				<form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
					
					<div class="modal-body form-group">  
                        <div class="form-group">
                            <label class="control-label">Nombre:</label>
							<input id="name" type="text" class="form-control" name="name" value="" placeholder="Ingrese el nombre." required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="email" class=" control-label">E-Mail:</label>
							<input id="email" type="email" class="form-control" name="email" value="" placeholder="Ingrese el e-mail." required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">Contraseña:</label>
							<input id="password" type="password" class="form-control" name="password" placeholder="Ingrese la contraseña." required>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class=" control-label">Confirmar contraseña:</label>
							<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Ingrese nuevamente la contraseña." required>
                        </div>
                    </div>

                    <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
        			<h4 class="modal-title">Eliminar usuario</h4>
				</div>
				<div class="modal-body">
					<p>¿Está seguro que desea eliminar el usuario?</p>
					<label id="name">Nombre</label>
				</div>
				<div class="modal-footer ">						
					<form method="POST" action="" id="delete">
						{{ method_field('DELETE') }}
						{{ csrf_field() }}
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-danger btn-delete">Eliminar</button>
					</form>
				</div>
			</div>			
		</div>		
	</div>
@stop

@section('modal-edit')
	<div id="myModalEdit" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
        			<h4 class="modal-title">Editar usuario</h4>
				</div>
				
				<form method="POST" action="" id="edit">
					{{ method_field('PATCH') }}
					{{ csrf_field() }}
						<div class="modal-body">
							<div class="form-group">
					            <label class="control-label">Nombre:</label>
					            <input id="name-edit" type="text" class="form-control" name="name" value="" required autofocus>
					        </div>
					        <div class="form-group">
					            <label class="control-label">E-Mail:</label>
					     		<input id="email-edit" type="email" class="form-control" name="email" value="" required>
					        </div>					        
				        </div>
				        <div class="modal-footer form-group">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-primary btn-edit">Guardar</button>
						</div>
			    </form> 		
			</div>			
		</div>			
	</div>
@stop


@section('modal-search')
	<div id="myModalSearch" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Buscar tipo</h4>
		        </div>
	        	
	        	<form method="GET" action="/user/search">
		          	{{ csrf_field() }}

		            <div class="modal-body">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search" class="form-control input-sm" name="search">
			            		<option value="0" selected disabled>Seleccione el parámetro de búsqueda</option>
			            		<option value="name">Nombre</option>
			            		<option value="email">E-Mail</option>
			            	</select>                 
			            </div>  

			            <div class="form-group">
		                	<input type="text" class="form-control" name="value" value="" placeholder="Buscar...">
		              	</div>           
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-primary btn-edit">Buscar</button>
		            </div>
		        </form>
		          
	      	</div>      
	    </div> 
  	</div>
@endsection

@section('modal-reset')
	<div id="myModalReset" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Resetear contraseña</h4>
		        </div>
	        	
	        	<form method="POST" action="" id="reset">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group">
	                        <label for="password" class="control-label">Contraseña:</label>
							<input id="password-reset" type="password" class="form-control" name="password" required autofocus>
	                    </div>

	                    <div class="form-group">
	                        <label for="password-confirm" class="control-label">Confirmar Contraseña:</label>
	                        <input id="password-reset-confirm" type="password" class="form-control" name="password_confirmation" required>
	                    </div>          
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-primary btn-edit">Restablecer</button>
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
	
	<div class="col-md-12 row " >   
		<div class="col-md-6" align="right">
			<h4 class="text-info"  align="right">USUARIOS</h4> 
		</div>    
				
		<div class="col-md-6 button-table" align="right">
			<button class="btn btn-success" data-toggle="modal" data-target="#myModalAdd" role="button"><span class="glyphicon glyphicon-plus"></span> Agregar </button>
			<button class="btn btn-primary" data-toggle="modal" data-target="#myModalSearch" role="button"><span class="glyphicon glyphicon-search"></span> Buscar </button>
		</div>
			
	</div>

	<div class="row col-md-12">	
		<table class="table table-striped">
		    <thead>
		    	<tr>
			        <th>Nombre</th>
			        <th>E-Mail</th>
			        <th colspan="3" class="centered">Operación</th>
		      	</tr>
		    </thead>
		    <tbody>
		    	@foreach($users as $user)
		      		<tr>
		        		<td>{{ $user->name }}</td>
		        		<td>{{ $user->email }}</td>						
						<td align="right" data-toggle="tooltip" data-placement="top" title="Editar" data-container="body"><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModalEdit" data-id="{{$user->id}}"><span class="glyphicon glyphicon-pencil"></span></button></td>
						<td align="right" data-toggle="tooltip" data-placement="top" title="Eliminar" data-container="body"><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModalDelete" data-id="{{$user->id}}"><span class="glyphicon glyphicon-trash"></span></button></td>
						<td align="right" data-toggle="tooltip" data-placement="top" title="Cambiar contraseña" data-container="body"><button class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModalReset" data-id="{{$user->id}}"><span class="glyphicon glyphicon-lock"></span></button></td>
			        			        	
		     		</tr>
		     	@endforeach	     
		    </tbody>
		</table>		
	</div>
@endsection

@section('script')
	<script>
        $('#myModalDelete').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); // Button that triggered the modal
		  	var user_id = button.data('id');

			modalDelete("user", user_id);
		});

		$('#myModalEdit').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
		  	var user_id = button.data('id')

		  	modalEdit("user",user_id);
		});

		$('#myModalReset').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
		  	var user_id = button.data('id')

		  	modalReset(user_id);
		});
	</script>
@endsection