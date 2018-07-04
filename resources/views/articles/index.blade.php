@extends('layouts.app')

@section('modal-add')
	<div id="myModalAdd" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Agregar Artículo</h4>
		        </div>

				<form method="POST" action="/article/add">
					{{ csrf_field()}}

					<div class="modal-body form-group">  

			            <div class="form-group">
			                <label class="control-label">Categoría:</label>
			            	<select id="category_id" class="form-control input-sm" name="category_id" required>
			          			<option value="" selected disabled>Seleccione la categoría:</option>
			          			@foreach($categories as $category)
				                  	<option value="{{ $category->id }}">{{ $category->name }}</option>
				                @endforeach
			          		</select>
			            </div>

			            <div class="form-group conditional-product">
			            	<label class="control-label">Pieza</label>
			            	<select id="product_id" class="form-control input-sm" name="product_id">
			          			<option value="" selected disabled>Seleccione la pieza:</option>
			          			@foreach($products as $product)
				                  	<option value="{{ $product->id }}">{{ $product->type_name}} - {{ $product->name }}</option>
				                @endforeach
			          		</select>
			            </div>

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

@section('modal-edit')
  	<div id="myModalEdit" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Editar Artículo</h4>
		        </div>
	        	
	        	<form method="POST" action="" id="edit">
		          	{{ method_field('PATCH') }}
		          	{{ csrf_field() }}

		          	<div class="modal-body form-group">
			            <div class="form-group">
			                <label class="control-label">Categoría:</label>
			            	<select id="category_id" class="form-control input-sm" name="category_id" required>
			          			<option value="" selected disabled>Seleccione la categoría:</option>
			          			@foreach($categories as $category)
				                  	<option value="{{ $category->id }}">{{ $category->name }}</option>
				                @endforeach
			          		</select>
			            </div>

			            <div class="form-group" id="product-edit">
			            	<label class="control-label">Pieza</label>
			            	<select id="product_id" class="form-control input-sm" name="product_id">
			          			<option value="" selected disabled>Seleccione la pieza:</option>
			          			@foreach($products as $product)
				                  	<option value="{{ $product->id }}">{{ $product->type_name}} - {{ $product->name }}</option>
				                @endforeach
			          		</select>
			            </div>

			            <div class="form-group">
			              	<label class="control-label">Nombre:</label>
			              	<input type="text" class="form-control" name="name" id="name" value="" required autofocus>
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
		            <h4 class="modal-title">Eliminar Artículo</h4>
		        </div>
		        <div class="modal-body">
		          <p>¿Está seguro que desea eliminar el artículo?</p>
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

@section('modal-search')
	<div id="myModalSearch" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Buscar Artículo</h4>
		        </div>
	        	
	        	<form method="GET" action="/article/search">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search" class="form-control input-sm" name="search">
			            		<option value="" selected disabled>Seleccione el parámetro de búsqueda</option>
			            		<option value="name">Nombre</option>
			            		<option value="category">Categoría</option>
			            		<option value="product">Pieza</option>
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
	        	
	        	<form method="POST" action="/article/import" enctype="multipart/form-data">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">
			            <div class="form-group">
			            	<label>Cargar archivo:</label>
		                	<input type="file" class="form-control-file" name="articles_file" id="file">
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
	        	
	        	<form method="GET" action="/article/export">
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
					<th scope="col">Categoría</th>
					<th scope="col">Pieza</th>
					<th scope="col">Existencia</th>
					<th scope="col" colspan="2" class="text-center">Operación</th>
				</tr>
			</thead>
			<tbody>
				@foreach($articles as $article)
					<tr>
						<td>{{ $article->name }}</td>
						<td>{{ $article->category->name }}</td>
						@if(is_null($article->product_id))
							<td></td>
						@else
							<td>{{$article->product->type_name}} - {{$article->product->name}}</td>
						@endif
						<td>{{ $article->stock }} </td>
						<td align="right"><span data-toggle="tooltip" data-placement="top" title="Editar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEdit" data-id="{{$article->id}}"><span class="fa fa-pencil"></span></button></span></td>
	          			<td><span data-toggle="tooltip" data-placement="top" title="Eliminar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$article->id}}"><span class="fa fa-trash"></span></button></span></td>
						
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row justify-content-center">
    	{{ $articles->links('vendor.pagination.bootstrap-4') }}
  	</div>
@endsection

@section('script')
  <script> 
  	productShow();
    $('#myModalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // BOTÓN QUE EJECUTÓ EL MODAL
        var article_id = button.data('id')

        modalDelete("article", article_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {    	
        var button = $(event.relatedTarget); 
        var article_id = button.data('id');

       modalEdit("article",article_id);
    });       
  </script>
@endsection