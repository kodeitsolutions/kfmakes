@extends('layouts.app')

@section('modal-add')
  <div id="myModalAdd" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Agregar componente</h4>
        </div>


        <form method="POST" action="/component/add">
          {{ csrf_field()}}

          <div class="modal-body form-group">  
            <div class="form-group {{ $errors->has('type_id') ? ' has-error' : '' }}">
                <label class="control-label c">Componente:</label>
              <select id="type_id" class="form-control input-sm" name="type_id">
                <option selected disabled>Seleccione el componente:</option>
                @foreach($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>                    

                @if ($errors->has('type_id'))
                    <span class="help-block"><strong>{{ $errors->first('type_id') }}</strong></span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="control-label">Nombre:</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Ingrese el nombre." required autofocus>  
                

                @if ($errors->has('name'))
                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
                <label class="control-label">Costo:</label>
                <input type="number" value="0.000" min="0" step="0.001" class="form-control" name="cost" value="{{ old('cost') }}" placeholder="Ingrese el costo." required>  
                

                @if ($errors->has('cost'))
                    <span class="help-block"><strong>{{ $errors->first('cost') }}</strong></span>
                @endif
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
              <h4 class="modal-title">Eliminar Pieza</h4>
            </div>
            <div class="modal-body">
              <p>¿Está seguro que desea eliminar la pieza?</p>
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
          <h4 class="modal-title">Editar componente</h4>
        </div>
          
        <form method="POST" action="" id="edit">
          {{ method_field('PATCH') }}
          {{ csrf_field() }}

          <div class="modal-body form-group">  
            <div class="form-group ">
              <label class="control-label">Tipo:</label>
              <select id="type_id" class="form-control input-sm" name="type_id">
                @foreach($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach    
              </select>             
            </div>  

            <div class="form-group">
              <label class="control-label">Nombre:</label>
              <input type="text" class="form-control" name="name" id="name" value="" required autofocus>
            </div>

            <div class="form-group">
              <label class="control-label">Costo:</label>
              <input type="number" class="form-control" min="0" step="0.001" name="cost" id="cost" value="" required>
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
          <h4 class="modal-title">Buscar componente</h4>
        </div>
          
        <form method="GET" action="/product/search">
          {{ csrf_field() }}

            <div class="modal-body form-group">  
              <div class="form-group ">
                <label>Buscar por:</label>
                <select  id="search" class="form-control input-sm" name="search" required>
                  <option value="0" selected disabled>Seleccione el parámetro de búsqueda</option>                  
                  <option value="type">Tipo</option>
                  <option value="name">Nombre</option>
                  <option value="cost">Costo</option>
                </select>                 
              </div>  

              <div class="form-group">
                <input type="text" class="form-control" name="value" value="{{ old('value') }}" placeholder="Buscar...">
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
  <div class="col-md-12 row button-table" align="right">  
    <a href="/product/create" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span> Agregar </a>
    <button class="btn btn-primary" data-toggle="modal" data-target="#myModalSearch" role="button"><span class="glyphicon glyphicon-search"></span> Buscar </button>
  </div>
  <div class="row col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Nombre</th>
          <th>Costo KFD</th>
          <th>Costo EKF</th>
          <th colspan="2" class="centered" >Operación</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $index => $product)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $product->type->name }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->cost_KFD }}</td>
            <td>{{ $product->cost_EKF }}</td>           
            <td align="right" data-toggle="tooltip" data-placement="top" title="Editar" data-container="body"><a href="/product/{{$product->id}}/edit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModalDelete" data-id="{{$product->id}}"><span class="glyphicon glyphicon-trash"></span></button></td>
            
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
        var product_id = button.data('id')
        
        modalDelete("product", product_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var product_id = button.data('id');

        modalEdit("product",product_id);
    });       
  </script>
@endsection