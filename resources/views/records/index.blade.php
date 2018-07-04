@extends('layouts.app')

@section('modal-add')
	<div id="myModalAdd" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title">Agregar Movimiento de Inventario</h4>
		        </div>

				<form method="POST" action="/record/add">
					{{ csrf_field()}}

					<div class="modal-body form-group">  

						<div class="form-group">
							<label class="control-label">Motivo</label>
			            	<select id="motive" class="form-control input-sm" name="motive" required>
			            		<option value="" selected disabled>Seleccione un motivo:</option>
			            		<option value="entrada">Entrada</option>
			            		<option value="salida">Salida</option>
			            	</select>
						</div>

						<div class="form-group">
							<label class="control-label">Fecha:</label>
							<input type="text" class="form-control" name="date" id="date" value="" required>
						</div>

			            <div class="form-group">
			                <label class="control-label">Artículo:</label>
			            	<select id="article_id" class="form-control input-sm" name="article_id" required>
			          			<option value="" selected disabled>Seleccione el artículo:</option>
			          			@foreach($articles as $article)
				                  	<option value="{{ $article->id }}">{{ $article->name }}</option>
				                @endforeach
			          		</select>
			            </div>

			            <div class="form-group">
			            	<label class="control-label">Ubicación</label>
			            	<select id="location_id" class="form-control input-sm" name="location_id" required>
			          			<option value="" selected disabled>Seleccione la ubicación:</option>
			          			@foreach($locations as $location)
				                  	<option value="{{ $location->id }}">{{ $location->name }}</option>
				                @endforeach
			          		</select>
			            </div>			            

			            <div class="form-group">
			                <label class="control-label">Cantidad:</label>
			                <input type="number" value="" min="0" step="0.01" class="form-control" name="quantity" value="" placeholder="Ingrese la cantidad." required autofocus>  
			            </div>

			            <div class="form-group">
			                <label class="control-label">Comentario:</label>
			                <textarea class="form-control" name="comment" rows="2"></textarea>
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
		            <h4 class="modal-title">Editar Movimiento</h4>
		        </div>
	        	
	        	<form method="POST" action="" id="edit">
		          	{{ method_field('PATCH') }}
		          	{{ csrf_field() }}

		          	<div class="modal-body form-group">
			            <div class="form-group">
							<label class="control-label">Motivo</label>
			            	<select id="motive" class="form-control input-sm" name="motive" required>
			            		<option value="" selected disabled>Seleccione un motivo:</option>
			            		<option value="entrada">Entrada</option>
			            		<option value="salida">Salida</option>
			            	</select>
						</div>

						<div class="form-group">
							<label class="control-label">Fecha:</label>
							<input type="text" class="form-control" name="date_edit" id="date_edit" value="" required>
						</div>

			            <div class="form-group">
			                <label class="control-label">Artículo:</label>
			            	<select id="article_id" class="form-control input-sm" name="article_id" required>
			          			<option value="" selected disabled>Seleccione el artículo:</option>
			          			@foreach($articles as $article)
				                  	<option value="{{ $article->id }}">{{ $article->name }}</option>
				                @endforeach
			          		</select>
			            </div>

			            <div class="form-group">
			            	<label class="control-label">Ubicación</label>
			            	<select id="location_id" class="form-control input-sm" name="location_id" required>
			          			<option value="" selected disabled>Seleccione la ubicación:</option>
			          			@foreach($locations as $location)
				                  	<option value="{{ $location->id }}">{{ $location->name }}</option>
				                @endforeach
			          		</select>
			            </div>			            

			            <div class="form-group">
			                <label class="control-label">Cantidad:</label>
			                <input type="number" id="quantity" value="" min="0" step="0.01" class="form-control" name="quantity" value="" placeholder="Ingrese la cantidad." required autofocus>  
			            </div>

			            <div class="form-group">
			                <label class="control-label">Comentario:</label>
			                <textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
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
		            <h4 class="modal-title">Eliminar Movimiento</h4>
		        </div>
		        <div class="modal-body">
		          <p>¿Está seguro que desea eliminar el Movimiento?</p>
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
		            <h4 class="modal-title">Buscar movimiento</h4>
		        </div>
	        	
	        	<form method="GET" action="/record/search">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search_record" class="form-control input-sm" name="search_record">
			            		<option value="" selected disabled>Seleccione el parámetro de búsqueda</option>
			            		<option value="motive">Motivo</option>
			            		<option value="date">Fecha</option>
			            		<option value="article">Artículo</option>
			            		<option value="location">Ubicación</option>
			            		<option value="comment">Comentario</option>
			            	</select>                 
			            </div>

			            <div class="form-group conditional-date">
			            	<label class="control-label">Fecha:</label>
							<input type="text" class="form-control date" name="date_search" id="date_search" value="">
			            </div>  

			            <div class="form-group search-value">
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
		                	<input type="file" class="form-control-file" name="records_file" id="file">
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
	        	
	        	<form method="GET" action="/record/export">
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
					<th scope="col">#</th>
					<th scope="col">Motivo</th>
					<th scope="col">Fecha</th>
					<th scope="col">Artículo</th>
					<th scope="col">Cantidad</th>
					<th scope="col">Ubicación</th>
					<th scope="col">Comentario</th>
					<th scope="col" colspan="2" class="text-center">Operación</th>
				</tr>
			</thead>
			<tbody>
				@foreach($records as $index => $record)
	         	 	<tr>          
	            		<th scope="row"> {{ $index + 1 }}</th>
						<td>@if($record->motive == 'entrada') Entrada @else Salida @endif</td>
						<td>{{ $record->dateView() }}</td>
						<td>{{ $record->article->name }}</td>
						<td>{{ $record->quantity }}</td>
						<td>{{ $record->location->name }}</td>
						<td>{{ $record->comment}}</td>
						<td align="right"><span data-toggle="tooltip" data-placement="top" title="Editar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalEdit" data-id="{{$record->id}}"><span class="fa fa-pencil"></span></button></span></td>
           				<td><span data-toggle="tooltip" data-placement="top" title="Eliminar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$record->id}}"><span class="fa fa-trash"></span></button></span></td>				
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row justify-content-center">
    	{{ $records->links('vendor.pagination.bootstrap-4') }}
  	</div>
@endsection

@section('script')
  <script> 
  	recordSearch();
    $('#myModalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // BOTÓN QUE EJECUTÓ EL MODAL
        var record_id = button.data('id')

        modalDelete("record", record_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {    	
        var button = $(event.relatedTarget); 
        var record_id = button.data('id');

       modalEdit("record",record_id);
    });       
  </script>
@endsection