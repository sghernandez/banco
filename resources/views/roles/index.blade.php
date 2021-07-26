@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Listado de Roles</h2>
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
     <th>Rol</th>
     <th width="280px">
         Acciones
         <span style="float: right">
            <a href="{{ route('roles.create')}}">Nuevo Rol</a>
         </span>
     </th>
  </tr>
    @foreach ($roles as $key => $r)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $r->name }}</td>
        <td></td>
    </tr>
    @endforeach
</table>

{!! $roles->render() !!}
@endsection