@extends('layouts.app')


@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
      <div class="pull-left">
          <h2>Listado de Usuarios</h2>
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
   <th>Apellidos</th>
   <th>Documento</th>
   <th>Email</th>
   <th>Roles</th>
   <th width="280px">
    Acciones
    <span style="float: right">
       <a href="{{ route('users.create')}}">Nuevo Usuario</a>
    </span>
</th>
 </tr>
 @foreach ($data as $key => $user)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->lastname }}</td>
    <td>{{ $user->document }}</td>
    <td>{{ $user->email }}</td>
    <td>
      @if(!empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
           <label class="badge badge-success">{{ $v }}</label>
        @endforeach
      @endif
    </td>
    <td>  
      @if($user->id !== 1)                
      <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Editar</a>  
      @endif       
  </td>    
  </tr>
 @endforeach
</table>

{!! $data->render() !!}
@endsection