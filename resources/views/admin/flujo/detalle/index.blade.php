@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Flujo de aprobación Detalle</h1>
@stop

@section('content')
@if (session('info'))
        
<div class="alert alert-primary" role="alert">
    <strong>¡Éxito!</strong> {{session('info')}}
</div>

@endif  

<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.flujodet.store']) !!}

        <div class="form-group">
            {{ Form::hidden('flujo_id', $FlujoAprobacion->id) }}
            {!! Form::label('user', 'user:') !!}
            {!! Form::select('user',$users, null, ['placeholder' => 'Seleccione usuario...','class'=>'form-control']) !!}
            
            @error('user')
                <span class="invalid-feedback">
                    <strong>{{$message}}</strong>
                </span>
            @enderror
        
        </div>
        
            {!! Form::submit('Agregar Aprobador', ['class' => 'btn btn-primary mt-2']) !!}

        {!! Form::close() !!}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Posicion</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($detalles as $detalle)
                    <tr>
                        <td>{{$detalle->id}}</td>
                        <td>{{$detalle->posicion}}</td>
                        <td>{{$detalle->name}}</td>
                        <td>
                            <form action="{{route('admin.flujodet.destroy',$detalle->id)}}" method="POST">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm btn-flat" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    
                @empty
                <tr>
                    <td colspan="4">No hay registros</td>
                </tr>
                
                    
                @endforelse

            </tbody>

        </table>
    </div>
</div>

@stop

@section('css')
   
@stop

@section('js')


@stop