@extends('layouts.app')


@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        @if (count($errors) > 0)
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
 <div class="card-header"><h4>Nuevo Rol<h4></div> 
  <div class="card-body">
        {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
        <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nombre:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Nombre', 'class' => 'form-control', 'required' => 'required')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Permisos:</strong>
                <br/>
                @foreach($permission as $value)
                    <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                    {{ $value->name }}</label>
                <br/>
                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('roles.index') }}" class="btn btn-success"> Regresar</a>
        </div>
    </div>

    {!! Form::close() !!}
       </div>
     </div>
   </div>
  </div>
</div>
@endsection