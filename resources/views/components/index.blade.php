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
            <div class="form-group">
                <label class="control-label c">Componente:</label>
              <select id="type_id" class="form-control input-sm" name="type_id" required>
                <option value="" selected disabled>Seleccione el componente:</option>
                @foreach($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select> 
            </div>

            <div class="form-group">
                <label class="control-label">Nombre:</label>
                <input type="text" class="form-control" name="name" value="" placeholder="Ingrese el nombre." required autofocus>  
            </div>

            <div class="form-group">
                <label class="control-label">Costo:</label>
                <input type="number" value="" min="0" step="0.001" class="form-control" name="cost" value="" placeholder="Ingrese el costo." required>  
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
              <h4 class="modal-title">Eliminar Componente</h4>
            </div>
            <div class="modal-body">
              <p>¿Está seguro que desea eliminar el componente?</p>
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
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
          
        <form method="GET" action="/component/search">
          {{ csrf_field() }}

            <div class="modal-body form-group">  
              <div class="form-group ">
                <label>Buscar por:</label>
                <select  id="search" class="form-control input-sm" name="search" >
                  <option value="" selected disabled>Seleccione el parámetro de búsqueda</option>                  
                  <option value="type">Tipo</option>
                  <option value="name">Nombre</option>
                  <option value="cost">Costo</option>
                </select>                 
              </div>  

              <div class="form-group">
                <input type="text" class="form-control" name="value" value="" placeholder="Buscar...">
              </div>           
            </div>

            <div class="modal-footer form-group">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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

  <div class="row float-right">
    <button class="btn btn-success mr-3" data-toggle="modal" data-target="#myModalAdd" role="button"><span class="fa fa-plus"></span> Agregar </button>
    <button class="btn btn-primary mr-3" data-toggle="modal" data-target="#myModalSearch" role="button"><span class="fa fa-search"></span> Buscar </button>
  </div> 

  
  <table class="table table-hover">
    <thead class="thead-light">
      <tr>
        <th>Tipo</th>
        <th>Nombre</th>
        <th>Costo</th>
        <th colspan="2" class="text-center" >Operación</th>
      </tr>
    </thead>
    <tbody>
      @foreach($components as $component)
        <tr>
          <td>{{ $component->type->name }}</td>
          <td>{{ $component->name }}</td>
          <td>{{ $component->cost }}</td>
          
          <td align="right"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModalEdit" data-id="{{$component->id}}"><span class="fa fa-pencil"></span></span></button></td>
          <td><button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$component->id}}"><span class="fa fa-trash"></span></button></td>
          
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@section('script')
  <script type="text/javascript"> 
    $('#myModalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // BOTÓN QUE EJECUTÓ EL MODAL
        var component_id = button.data('id')

        modalDelete("component", component_id);
    });

    $('#myModalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var component_id = button.data('id');

        modalEdit("component",component_id);
    });       
  </script>
@endsection