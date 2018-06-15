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
						<button type="submit" class="btn btn-default">Eliminar</button>
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
					            <input id="name_edit" type="text" class="form-control" name="name" value="" required autofocus>
					        </div>
					        <div class="form-group">
					            <label class="control-label">E-Mail:</label>
					     		<input id="email_edit" type="email" class="form-control" name="email" value="" required>
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
			            		<option value="" selected disabled>Seleccione el parámetro de búsqueda</option>
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
		              	<button type="submit" class="btn btn-default">Buscar</button>
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
	                        <label for="password" class="control-label">Nueva contraseña:</label>
							<input id="password-reset" type="password" class="form-control" name="password" required autofocus>
	                    </div>

	                    <div class="form-group">
	                        <label for="password-confirm" class="control-label">Confirmar contraseña:</label>
	                        <input id="password-reset-confirm" type="password" class="form-control" name="password_confirmation" required>
	                    </div>          
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default">Restablecer</button>
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
	        	
	        	<form method="POST" action="/user/import" enctype="multipart/form-data">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">
			            <div class="form-group">
			            	<label>Cargar archivo:</label>
		                	<input type="file" class="form-control-file" name="users_file" id="file">
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
	        	
	        	<form method="GET" action="/user/export">
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
			
	<div class="row"> 
		<div class="mr-auto ml-3">
			<a href="/product" class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Salir config."><span class="fa fa-arrow-left"></span> <span class="fa fa-cog"></span></a>
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
			        <th scope="col">E-Mail</th>
			        <th scope="col" colspan="3" class="text-center">Operación</th>
		      	</tr>
		    </thead>
		    <tbody>
		    	@foreach($users as $user)
		      		<tr>
		        		<td>{{ $user->name }}</td>
		        		<td>{{ $user->email }}</td>						
						<td align="center"><span data-toggle="tooltip" data-placement="top" title="Editar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEdit" data-id="{{$user->id}}"><span class="fa fa-pencil"></span></button></span></td>
						<td align="center"><span data-toggle="tooltip" data-placement="top" title="Eliminar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$user->id}}"><span class="fa fa-trash"></span></button></span></td>
						<td align="center"><span data-toggle="tooltip" data-placement="top" title="Cambiar contraseña"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalReset" data-id="{{$user->id}}"><span class="fa fa-lock"></span></button></span></td>
			        			        	
		     		</tr>
		     	@endforeach	     
		    </tbody>
		</table>
	</div>
	<div class="row justify-content-center">
	    {{ $users->links('vendor.pagination.bootstrap-4') }}
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