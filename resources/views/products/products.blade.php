@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Listado de Productos</h2>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-striped">
  <tr>
     <th>No.</th>
     <th>Nombre</th>
     <th>Precio</th>
     <th>Detalle</th>
     <th width="280px">
         Acciones
         @can('product-manager')
         <span style="float: right">
            <a href="{{ route('products.create')}}">Nuevo Producto</a>
         </span>
         @endcan
     </th>
  </tr>
    @foreach ($products as $key => $r)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $r->name }}</td>
        <td>{{ $r->price }}</td>
        <td>{{ $r->detail }}</td>
        <td>            
            <a class="btn btn-primary" href="{{ route('products.edit', $r->id) }}">Editar</a>
            @can('product-manager')
            <form action="{{ route('products.destroy', $r->id) }}" method="post" style="display: inline">
              @csrf
              @method('DELETE')
             <button class="btn btn-danger" type="submit" onclick="return confirm('¿Está seguro de borrar el Producto?')">Borrar</button>
            </form>
            @endcan           
        </td>
    </tr>
    @endforeach
</table>

{!! $products->render() !!}
@endsection