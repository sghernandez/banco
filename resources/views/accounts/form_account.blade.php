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
 <div class="card-header"><h4>{{ isset($account) ? 'Editar' : 'Nueva' }} Cuenta<h4></div> 
  <div class="card-body">
  @if(isset($account))
    {!! Form::model($account, ['method' => 'PATCH','route' => ['accounts.update', $account->id]]) !!}  
  @else
     {!! Form::open(array('route' => 'accounts.store','method'=>'POST')) !!}
  @endif
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Usuario:</strong>            
                     {!! Form::select('user_id', $users, (isset($account) ? $account->user_id : 0), array('class' => 'form-control')) !!}                             
                </div>
            </div>            

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>No. de Cuenta:</strong>
                {!! Form::text('number', null, array('minlegth' => 6, 'class' => 'form-control', 'required' => 'required')) !!}                
            </div>
        </div>
        
        @if(isset($account))        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Tipo acción:</strong>            
                 {!! Form::select('action', $actions, 0, array('class' => 'form-control')) !!}                             
            </div>
        </div> 
        @endif 
        <input type="hidden" name="saldo">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Valor {{ isset($account) ? '' : 'de Apertura' }}:</strong>
                {!! Form::number('amount', null, array('class' => 'form-control')) !!}                
            </div>
            @if(isset($account))
              <small style="color: red">{{ 'El saldo actual de la cuenta es: $'. number_format($saldo) }}</small>
            @endif
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
</div>
@endsection