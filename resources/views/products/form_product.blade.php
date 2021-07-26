@extends('layouts.app')


@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        @if (count($errors))
        <div class="alert alert-danger">
            <strong>La Información no puede ser guardada.</strong><br>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

<div class="card">
 <div class="card-header"><h4>{{ isset($product) ? 'Editar' : 'Nuevo' }} Producto<h4></div> 
  <div class="card-body">
  @if(isset($product))
    {!! Form::model($product, ['method' => 'PATCH','route' => ['products.update', $product->id]]) !!}  
  @else
     {!! Form::open(array('route' => 'products.store','method'=>'POST')) !!}
  @endif
        <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nombre:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Nombre', 'class' => 'form-control', 'required' => 'required')) !!}                
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Precio:</strong>
                {!! Form::number('price', null, array('placeholder' => 'Precio', 'class' => 'form-control', 'required' => 'required', 'min' => 10)) !!}                
            </div>
        </div>        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Detail:</strong>
                {!! Form::text('detail', null, array('placeholder' => 'Detalle', 'class' => 'form-control', 'required' => 'required')) !!}   
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('products') }}" class="btn btn-success"> Regresar</a>
        </div>
    </div>

    {!! Form::close() !!}
       </div>
     </div>
   </div>
  </div>
</div>
@endsection