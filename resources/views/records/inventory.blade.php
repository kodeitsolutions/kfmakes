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
							<label class="control-label">Motivo:</label>
			            	<select id="motive" class="form-control input-sm" name="motive" required>
			            		<option value="" selected disabled>Seleccione un motivo</option>
			            		<option value="entrada">Entrada</option>
			            		<option value="salida">Salida</option>
			            	</select>
						</div>

						<div class="form-group">
							<label class="control-label">Fecha:</label>
							<input type="text" class="form-control" name="date" id="date" value="{{ $date }}">
						</div>

			            <div class="form-group">
			                <label class="control-label">Artículo:</label>
			            	<select id="article_id" class="form-control input-sm" name="article_id" required>
			          			<option value="" selected disabled>Seleccione el artículo</option>
			          			@foreach($articles as $article)
				                  	<option value="{{ $article->id }}">{{ $article->name }}</option>
				                @endforeach
			          		</select>
			            </div>

			            <div class="form-group">
			            	<label class="control-label">Ubicación:</label>
			            	<select id="location_id" class="form-control input-sm" name="location_id">
			          			<option value="" selected disabled>Seleccione la ubicación</option>
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

@section('modal-search')
	<div id="myModalSearch" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Buscar Inventario</h4>
		        </div>
	        	
	        	<form method="GET" action="/inventory/search">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group">  
		               	<div class="form-group ">
			                <label>Buscar por:</label>
            				<select  id="search" class="form-control input-sm" name="search">
			            		<option value="" selected disabled>Seleccione el parámetro de búsqueda</option>   		
			            		<option value="article">Artículo</option>
			            		<option value="category">Categoría</option>
			            		<option value="product">Pieza</option>
			            		<option value="location">Ubicación</option>
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

@section('modal-move')
	<div id="myModalMove" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	      	<div class="modal-content">
		    	<div class="modal-header">
		            <h4 class="modal-title">Trasladar existencia</h4>
		        </div>
	        	
	        	<form method="GET" action="" id="move">
		          	{{ csrf_field() }}

		            <div class="modal-body form-group"> 
		            	<div class="form-group">
		            		<p id="article" class="font-weight-bold"></p>
		            	</div> 
		               	<div class="form-group ">
			                <label>Origen:</label>
            				<select  id="origin" class="form-control input-sm" name="origin" required>
			            		<option value="" selected disabled>Seleccione el origen</option>
			            		@foreach($locations as $location)
			            			<option value="{{ $location->id }}">{{ $location->name }}</option>
			            		@endforeach			            		
			            	</select>                 
			            </div>
			            <div class="form-group ">
			                <label>Destino:</label>
            				<select  id="destination" class="form-control input-sm" name="destination" required>
			            		<option value="" selected disabled>Seleccione el destino</option>
			            		@foreach($locations as $location)
			            			<option value="{{ $location->id }}">{{ $location->name }}</option>
			            		@endforeach			            		
			            	</select>                 
			            </div> 
			            <div class="form-group">
			                <label class="control-label">Cantidad:</label>
			                <input type="number" value="" min="1" step="0.01" class="form-control" name="quantity" value="" placeholder="Ingrese la cantidad." required autofocus>  
			            </div>	
			            <div class="form-group">
			                <label class="control-label">Comentario:</label>
			                <textarea class="form-control" name="comment" rows="2"></textarea>
			            </div>		                   
					</div>

		            <div class="modal-footer form-group">
		              	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		              	<button type="submit" class="btn btn-default" id="export">Aceptar</button>
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
			<a href="/record" class="btn btn-default btn-sm mr-3 border border-dark" role="button" data-toggle="tooltip" data-placement="top" title="Movimientos"> <span class="fa fa-list-ul"></span></a>	
			<span data-toggle="tooltip" data-placement="top" title="Filtrar">
		        <button type="button" class="btn btn-default btn-sm dropdown-toggle border border-dark" data-toggle="dropdown"><span class="fa fa-filter"></span> <span class="caret"></span></button>
		        <ul class="dropdown-menu" id="filter-list">
		          	<a href="" class="dropdown-item" id="all">Todos</a>
		          	<div class="dropdown-divider"></div>
		          	<form method="GET" action="/inventory/search" id="filter-form">
			            @foreach($categories as $category)
			              	<li class="dropdown-item"><input type="checkbox" class="form-check-input" name="category[]" id="filter" value="{{$category->id}}" />{{$category->name}}</li>
			            @endforeach
		            	<div class="dropdown-divider"></div>
		            	<a href="" class="dropdown-item" id="filter-button" onclick="submitForm('filter-form')">Aplicar</a> 
		          	</form>           
		        </ul>
		    </span>
		</div>
		<div class="ml-auto">			
			<span data-toggle="modal" data-target="#myModalAdd"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Agregar"><span class="fa fa-plus"></span></button></span>
			<span data-toggle="modal" data-target="#myModalSearch" style="display: none;"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Buscar"><span class="fa fa-search"></span></button></span>
		</div>	
	</div>
	
	<div class="table-responsive-sm">
		<table class="table table-hover">
			<thead class="thead-index">
				<tr class="text-white">
					<th scope="col">Artículo</th>
					<th scope="col">Categoría</th>
					<th scope="col">Pieza</th>
					<th scope="col">Estados Unidos</th>
					<th scope="col">Venezuela</th>
					<th scope="col" class="text-center">Operación</th>
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
						@if($records->contains('id',$article->id))							
							@php $filter = collect([]); 
							$filter = $records->where('id',$article->id) @endphp
							@if($filter->contains('country', 'Estados Unidos') and $filter->contains('country','Venezuela'))
								@foreach($filter as $query)
									@if($query->id == $article->id and $query->country == 'Estados Unidos')
										<td>{{ $query->stock }}</td>
									@elseif($query->id == $article->id and $query->country == 'Venezuela')
										<td>{{ $query->stock }}</td>													
									@endif
								@endforeach
							@elseif($filter->contains('country', 'Estados Unidos') and !$filter->contains('country','Venezuela'))
								@foreach($filter as $query)
									<td>{{ $query->stock }}</td>									
									<td>0.00</td>									
								@endforeach
							@else
								@foreach($filter as $query)
									<td>0.00</td>
									<td>{{ $query->stock }}</td>
								@endforeach
							@endif
						@else
							<td>0.00</td>
							<td>0.00</td>
						@endif
						
						<td align="center"><span data-toggle="tooltip" data-placement="top" title="Trasladar"><button class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModalMove" data-id="{{$article->id}}"><span class="fa fa-exchange"></span></button></span></td>				
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row justify-content-center">
    	{{-- $articles->links('vendor.pagination.bootstrap-4') --}}
  	</div>
@endsection

@section('script')
  <script> 
    $('#myModalMove').on('show.bs.modal', function (event) {    	
        var button = $(event.relatedTarget); 
        var article_id = button.data('id');

       	modalMove("article",article_id);
    });     
  </script>
@endsection