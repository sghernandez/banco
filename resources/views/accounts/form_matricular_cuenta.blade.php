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

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
   @endif    

<div class="card">
 <div class="card-header">
     <h4>Matricular cuenta de Tercero<h4>
     <small>Si no aparece ninguna cuenta es porque ya están todas matriculadas o no hay cuentas de Terceros activas.</small>
</div> 
  <div class="card-body">
    {!! Form::open(array('route' => 'matricular', 'method' => 'POST')) !!}

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">           
                     <h3>{{ $user->name. ' '. $user->lastname }}</h3>
                </div>
            </div>    
         
            <input type="hidden" name="tran_terceros" value="1">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Cuenta de Tercero a Matricular:</strong>            
                     {!! Form::select('ctercero', $acc_terceros, 0, array('class' => 'form-control', 'required' => 'required')) !!}                             
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