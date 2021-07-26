@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
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
 <div class="card-header"><h4>Transferencias a cuenta de Terceros<h4></div> 
  <div class="card-body">
    {!! Form::open(array('route' => 'transferencia', 'method' => 'POST')) !!}

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">           
                     <h3>{{ $user->name. ' '. $user->lastname }}</h3>
                </div>
            </div>    

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Mi Cuenta Origen:</strong>            
                     {!! Form::select('origen', $accounts, 0, array('class' => 'form-control', 'required' => 'required')) !!}                             
                </div>
            </div>             
            <input type="hidden" name="tran_terceros" value="1">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Cuenta Tercero Destino:</strong>            
                     {!! Form::select('destino', $acc_terceros, 0, array('class' => 'form-control', 'required' => 'required')) !!}                             
                </div>
            </div>  
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Valor Transferencia:</strong>
                    {!! Form::number('valor', null, array('class' => 'form-control', 'required' => 'required', 'min' => 1)) !!}                
                </div>
            </div>             
         
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('accounts') }}" class="btn btn-success"> Regresar</a>
        </div>

    </div>

    {!! Form::close() !!}
       </div>
     </div>
   </div>
</div>


@endsection