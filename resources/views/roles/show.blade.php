@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Detalles de Rol</h2>
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Rol:</strong>
            {{ $role->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <strong>Permisos:</strong>
        <div class="form-group">            
            @if(is_object($rolePermissions))
              <label class="label label-success">@foreach($rolePermissions as $p) {{ $p->name }}<br> @endforeach</label>
            @endif
        </div>
    </div>
</div>
<div class="pull-right">
    <a href="{{ route('roles.index') }}" class="btn btn-primary">Regresar</a>
</div>
@endsection