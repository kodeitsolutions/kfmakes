@extends('layouts.app')

@section('navigation')
  @if (Auth::check())
    @include('navigation')
  @endif
@endsection

@section('content')
	@if($route == 'edit')
    	<p class="text-center font-weight-bold">Editar pieza</p>
      
      	<form method="POST" action="/product/{{ $product->id }}">
      	{{ method_field('PATCH') }}    
    @else
      	<p class="text-center font-weight-bold">Agregar pieza</p>
      
      	<form method="POST" action="/product/add" id="create">
    @endif	
        {{ csrf_field()}}
		<div class="row col-md-12 form-group {{ $errors->has('type_id') ? ' has-error' : '' }}">			
			<label class="control-label col-md-2">Tipo: </label>
			<div class="col-md-4">
				<select id="type_id" class="form-control input-sm" name="type_id" >
					<option selected disabled>Seleccione el tipo</option>
					@foreach($type_products as $type_product)
						<option value="{{ $type_product->id }}" @if (old('type_id', $product->type_id) == $type_product->id) selected @endif>{{ $type_product->name}}</option>
					@endforeach
				</select>
			</div>		
		</div>	

		<div class="row col-md-12 form-group {{ $errors->has('name') ? ' has-error' : '' }}">
			<label class="control-label col-md-2">Nombre: </label>
			<div class="col-md-10">
				<input class="form-control" type="text" name="name" value="{{ old('name', $product->name) }}" autofocus placeholder="Nombre de la pieza">
			</div>
		</div>

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

		<div id="accordion" role="tablist" aria-multiselectable="true">
			@foreach($type_components as $type_component)
				<div id="accordion">
				  <div class="card">
				    <div class="card-header" id="heading{{ $type_component->id }}">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $type_component->id }}" class="text-white font-weight-bold">
				        	{{ $type_component->name }} <span class="fa fa-chevron-down pull-right text-white"></span>
				        </a>				      
				    </div>

				    <div id="collapse{{ $type_component->id }}" class="collapse show" aria-labelledby="heading{{ $type_component->id }}" >
				      <div class="table-responsive">
				        <table class="table table-striped">
				          <thead>
				            <tr>
					            <th>Descripción</th>
					            <th>Costo</th>
					            <th>Cantidad</th>
				            </tr>            
				          </thead>
				          <tbody>
				          	@foreach($type_component->components->sortBy('name') as $t_component)
					            <tr>
					              	<td>{{$t_component->name}}</td>
					              	<td>{{$t_component->cost}}</td>
					              	@if($route == 'edit')
					              		@if($components->contains('id',$t_component->id))				              		
							              	@foreach($components as $component)
							              		@if($t_component->id == $component->id)
													<td><input class="form-control" type="number" name="{{$t_component->id}}" value="{{ $component->id == $t_component->id ? $component->pivot->quantity : old($t_component->id,'') }}" min="0" step="0.001" placeholder="0,000"></td>

												@endif
							           		@endforeach
							           	@else
							           		<td><input class="form-control" type="number" name="{{$t_component->id}}" value="{{ old($t_component->id) }}" min="0" step="0.001" placeholder="0,000"></td>
							           	@endif		           	
						           	@else
						           		<td><input class="form-control" type="number" name="{{$t_component->id}}" value="{{ old($t_component->id) }}" min="0" step="0.001" placeholder="0,000"></td>
						           	@endif
					            </tr>
					        @endforeach
				          </tbody>
				        </table>			        
				      </div>
				    </div>
				  </div>			  
				</div>
			@endforeach	
		</div>	

		<div class="row form-group float-right" ">
	        <a href="javascript:history.go(-1)" class="btn btn-default mr-3" role="button">Cancelar</a>
	        <button type="submit" class="btn btn-default">Guardar</button>
	    </div>      
    </form>
@endsection

@section('script')
	<script>		
		$(window).bind('beforeunload', function(){
		    return "Any changes will be lost";
		});

		// Form Submit
		$(document).on("submit", "form", function(event){
		    // disable unload warning
		    $(window).off('beforeunload');
		});
	</script>
@endsection