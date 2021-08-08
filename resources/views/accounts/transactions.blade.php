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
    $options[null] = 'Seleccione una Opción';
    $tran_origen = Config::get('app.tran_origen'); 

    foreach ($tran_origen as $key => $o) { $options[$key] = $o; } ?>
<br>
    
{!! Form::open(array('route' => 'listar_transferencias', 'method' => 'POST')) !!}
    @csrf
    <!-- State -->
    <h6><b>Destino / Origen</b></h6>
    <a href="{{ url('listar-transferencias') }}" class="btn btn-warning">Recargar</a>
      {!! Form::select('type', $options, (isset($data['type']) ? $data['type'] : null), array('class' => 'form-control col-sm-6', 'required' => 'required', 'style' => 'display: inline')) !!}                             

    <!-- Send button -->
    <button type=submit class="btn btn-primary">Buscar</button>    
{!! Form::close() !!} <br>  

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
    <?php  if(isset(${$r->id})){ $j--; }
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


{{ $transactions->appends($data)->links() }}
@endsection