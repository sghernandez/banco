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
<div class="card-header"><h4>{{ isset($user) ? 'Editar' : 'Nuevo' }} Usuario<h4></div> 
    <div class="card-body">
    @if(isset($user))
      {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}  
    @else
       {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
    @endif
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nombre:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Nombre','class' => 'form-control', 'required' => 'required')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Apellidos:</strong>
            {!! Form::text('lastname', null, array('placeholder' => 'Apellidos','class' => 'form-control', 'required' => 'required')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>No. Documento:</strong>
            {!! Form::number('document', null, array('placeholder' => 'No. Documento','class' => 'form-control', 'required' => 'required')) !!}
        </div>
    </div>    
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'required' => 'required')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Confirme Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirmar Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Rol:</strong>            
             {!! Form::select('roles[]', $roles, (isset($userRole) ? $userRole : []), array('class' => 'form-control', 'multiple')) !!}          
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ url('users') }}" class="btn btn-success"> Regresar</a>
    </div>
</div>
{!! Form::close() !!}
    </div>
   </div>  
  </div>
 </div>
</div>
@endsection