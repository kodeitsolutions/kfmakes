@extends('layouts.app')

@section('modal-add')
	<div id="myModalAdd" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Agregar Categoría</h4>
		        </div>

				<form method="POST" action="/category/add">
					{{ csrf_field()}}

					<div class="modal-body form-group"> 			            

			            <div class="form-group">
			                <label class="control-label">Nombre:</label>
			                <input type="text" class="form-control" name="name" value="" placeholder="Ingrese el nombre." required autofocus>
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
		            <h4 class="modal-title">Eliminar Categoría</h4>
		        </div>
		        <div class="modal-body">
		          <p>¿Está seguro que desea eliminar la categoría?</p>
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
		            <h4 class="modal-title">Editar Categoría</h4>
		        </div>
	        	
	        	<form method="POST" action="" id="edit">
		          	{{ method_field('PATCH') }}
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">		               	

			            <div class="form-group">
		                	<label class="control-label">Nombre:</label>
		                	<input type="text" class="form-control" name="name" id="name" value="" autofocus>
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
		            <h4 class="modal-title">Buscar categoría</h4>
		        </div>
	        	
	        	<form method="GET" action="/category/search">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search" class="form-control input-sm" name="search">
			            		<option value="" selected disabled>Seleccione el parámetro de búsqueda</option>
			            		<option value="name">Nombre</option>
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
	        	
	        	<form method="POST" action="/category/import" enctype="multipart/form-data">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">
			            <div class="form-group">
			            	<label>Cargar archivo:</label>
		                	<input type="file" class="form-control-file" name="categories_file" id="file">
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
	        	
	        	<form method="GET" action="/category/export">
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
					<th scope="col" colspan="2" class="text-center">Operación</th>
				</tr>
			</thead>
			<tbody>
				@foreach($categories as $category)
					<tr>
						<td>{{ $category->name }}</td>
						
						<td align="right"><span data-toggle="tooltip" data-placement="top" title="Editar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEdit" data-id="{{$category->id}}"><span class="fa fa-pencil"></span></button></span></td>
	          			<td><span data-toggle="tooltip" data-placement="top" title="Eliminar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$category->id}}"><span class="fa fa-trash"></span></button></span></td>
						
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row justify-content-center">
    	{{ $categories->appends(Request::except('page'))->render('vendor.pagination.bootstrap-4') }}
  	</div>
@endsection

@section('script')
  <script> 
    $('#myModalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // BOTÓN QUE EJECUTÓ EL MODAL
        var category_id = button.data('id')

        modalDelete("category", category_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var category_id = button.data('id');

       modalEdit("category",category_id);
    });       
  </script>
@endsection