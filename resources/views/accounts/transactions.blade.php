@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Listado de Transferencias</h2>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<?php 
    $j = 1;
    $tipos = Config::get('app.transactions_type');
    $tran_origen = Config::get('app.tran_origen') ?>
<br>

<table class="table table-striped">
  <tr>
     <th>ID Tran</th>
     <th>No. de Cuenta</th>
     <th>Propietario</th>
     <th>Doc. Propietario</th>
     <th>Tipo Movimiento</th>
     <th></th>
     <th>Valor</th>
     <th>Fecha Movimiento</th>
  </tr>
    @foreach ($transactions as $key => $r) 
   
      <?php if(isset(${$r->id})){ $j--; }
            ${$r->id} = ($j % 2 == 1) ? 'bg-info text-white' : 'bg-light text-dark';
            $j++ ?>

    <tr class="{{ ${$r->id} }}">
        <td>{{ $r->id }}</td>
        <td>{{ $r->number }}</td>
        <td>{{ "$r->name $r->lastname"}}</td>
        <td>{{ $r->document }}</td>
        <td>{{ $tipos[$r->type] }}</td>
        <td>{{ $tran_origen[$r->type] }}</td>
        <td>{{ $r->amount }}</td>
        <td>{{ $r->created_at }}</td>
    </tr>
    @endforeach
</table>


{!! $transactions->render() !!}
@endsection