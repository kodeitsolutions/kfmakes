@extends('layouts.app')



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
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-warning">Eliminar</button>
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
          <h4 class="modal-title">Buscar componente</h4>
        </div>
          
        <form method="GET" action="/product/search">
          {{ csrf_field() }}

            <div class="modal-body form-group">  
              <div class="form-group ">
                <label>Buscar por:</label>
                <select  id="search" class="form-control input-sm" name="search">
                  <option value="0" selected disabled>Seleccione el parámetro de búsqueda</option>                  
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
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" id="saveButton">Buscar</button>
            </div>
        </form>
              
      </div>      
    </div> 
  </div>
@endsection

@section('navigation')
  @if (Auth::check())
    @include('navigation')
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

  <div class="row float-right">  
    <a href="/product/create" class="btn btn-success mr-3" role="button"> <span class="fa fa-plus"></span> Agregar </a>
    <button class="btn btn-info mr-3" data-toggle="modal" data-target="#myModalSearch" role="button"><span class="fa fa-search"></span> Buscar </button>
  </div>
  
  <table class="table table-hover">
    <thead class="thead-light">
      <tr>
        <th>#</th>
        <th>Tipo</th>
        <th>Nombre</th>
        <th>Costo KFD</th>
        <th>Costo EKF</th>
        <th colspan="2" class="text-center" >Operación</th>
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
          <td align="right" data-toggle="tooltip" data-placement="top" title="Editar" data-container="body"><a href="/product/{{$product->id}}/edit" class="btn btn-primary btn-sm"><span class="fa fa-pencil"></span></a></td>
          <td><button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModalDelete" data-id="{{$product->id}}"><span class="fa fa-trash"></span></button></td>
          
        </tr>
      @endforeach
    </tbody>
  </table>
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