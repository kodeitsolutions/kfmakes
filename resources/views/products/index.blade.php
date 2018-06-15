@extends('layouts.app')

@section('modal-show')
  <div id="myModalShow" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title-show"></h4>
        </div>         
        
          <div class="modal-body form-group">
            <p class="">Componentes</p>           
            <table class="table table-sm" id="description">
              <thead class="thead-light">
                <tr>
                  <td>#</td>
                  <td>Tipo</td>
                  <td>Nombre</td>
                  <td>Cantidad</td>
                </tr>
              </thead>
              <tbody id="description-body">                
              </tbody>
            </table>
          </div> 

          <div class="modal-footer form-group">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="showButton">Cerrar</button>
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
          <h4 class="modal-title">Buscar componente</h4>
        </div>
          
        <form method="GET" action="/product/search">
          {{ csrf_field() }}

            <div class="modal-body form-group">  
              <div class="form-group ">
                <label>Buscar por:</label>
                <select  id="search" class="form-control input-sm" name="search">
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
            
            <form method="POST" action="/product/import" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-body form-group">
                  <div class="form-group">
                    <label>Cargar archivo:</label>
                      <input type="file" class="form-control" name="products_file" id="file">
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
            
            <form method="GET" action="/product/export">
                {{ csrf_field() }}

                <div class="modal-body form-group">  
                    <div class="form-group ">
                      <label>Formato de archivo:</label>
                    <select  id="extension" class="form-control input-sm" name="extension">
                      <option value="" selected disabled>Seleccione el formato</option>
                      <option value="xls">XLS</option>
                      <option value="xlsx">XLSX</option>
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

  <div class="row"> 
    <div class="mr-auto ml-3">
      <span data-toggle="modal" data-target="#myModalImport"><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Importar" style="display: none"><span class="fa fa-upload"></span></button></span>
      <span data-toggle="modal" data-target="#myModalExport" disabled><button class="btn btn-default btn-sm mr-3" role="button" data-toggle="tooltip" data-placement="top" title="Exportar"><span class="fa fa-download"></span></button></span>   
      <span data-toggle="tooltip" data-placement="top" title="Filtrar">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle border border-dark" data-toggle="dropdown"><span class="fa fa-filter"></span> <span class="caret"></span></button>
        <ul class="dropdown-menu" id="filter-list">
          <a href="" class="dropdown-item" id="all">Todos</a>
          <div class="dropdown-divider"></div>
          <form method="GET" action="/product/search" id="filter-form">
            @foreach($types as $type)
              <li class="dropdown-item"><input type="checkbox" class="form-check-input" name="type[]" id="type" value="{{$type->id}}" />{{$type->name}}</li>
            @endforeach
            <div class="dropdown-divider"></div>
            <a href="" class="dropdown-item" id="filter-button" onclick="submitForm('filter-form')">Aplicar</a> 
          </form>           
        </ul>
      </span>
    </div>
    <div class="ml-auto">
      <a href="/product/create" class="btn btn-default btn-sm mr-3 border border-dark" role="button" data-toggle="tooltip" data-placement="top" title="Agregar"> <span class="fa fa-plus"></span></a>
      <span data-toggle="modal" data-target="#myModalSearch"><button class="btn btn-default btn-sm mr-3 border border-dark" role="button" data-toggle="tooltip" data-placement="top" title="Buscar"><span class="fa fa-search"></span></button></span>
    </div>  
  </div> 
  
  <div class="table-responsive-sm">  
    <table class="table table-hover">
      <thead class="thead-index">
        <tr class="text-white">
          <th scope="col">#</th>
          <th scope="col">Tipo</th>
          <th scope="col">Nombre</th>
          <th scope="col">Costo KFD</th>
          <th scope="col">Costo EKF</th>
          <th scope="col" colspan="3" class="text-center">Operación</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $index => $product)
          <tr>          
            <th scope="row"> {{ $index + 1 }}</th>
            <td>{{ $product->type->name }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->cost_KFD }}</td>
            <td>{{ $product->cost_EKF }}</td>
            <td align="right"><span data-toggle="tooltip" data-placement="top" title="Ver mas"><button id="show-button" class="btn btn-default btn-sm border border-dark" data-toggle="modal" data-target="#myModalShow" data-id="{{$product->id}}"><span class="fa fa-eye"></span></button></span></td>    
            <td align="center"><span  data-toggle="tooltip" data-placement="top" title="Editar"><a href="/product/{{$product->id}}/edit" class="btn btn-default btn-sm border border-dark" role="button"><span class="fa fa-pencil"></span></a></span></td>          
            <td  align="left"><span data-toggle="tooltip" data-placement="top" title="Eliminar"><button class="btn btn-default btn-sm border border-dark" data-toggle="modal" data-target="#myModalDelete" data-id="{{$product->id}}"><span class="fa fa-trash"></span></button></span></td>
            
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="row justify-content-center">
    {{ $products->links('vendor.pagination.bootstrap-4') }}
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

    $('#myModalShow').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var product_id = button.data('id');
        
        modalShow(product_id);
    });   
  </script>
@endsection