@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Listado de Cuentas</h2>
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
     <th>No. de Cuenta</th>
     <th>Propietario</th>
     <th>Doc. Propietario</th>
     <th>Saldo</th>
     <th>
         Estado
         <span style="float: right">
            <a href="{{ route('transferir')}}">Transferir</a>
         </span>
     </th>
  </tr>
    @foreach ($accounts as $key => $r) 
    <?php $accion = $r->status ? 'inactivar' : 'activar' ?>
    <tr>
        <td>{{ $r->number }}</td>
        <td>{{ $r->user->name. ' '. $r->user->lastname }}</td>
        <td>{{ $r->user->document }}</td>

        <td>{{ number_format(isset($totals[$r->id]) ? $totals[$r->id] : 0) }}</td>
        <td><b>{{ $r->status ? 'Activa' : 'Inactiva' }}</b></td>
    </tr>
    @endforeach
</table>

{!! $accounts->render() !!}
@endsection