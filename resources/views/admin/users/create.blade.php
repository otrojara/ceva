@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content') 
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'admin.users.store']) !!}

               @include('admin.users.partials.form')
                
                {!! Form::submit('Crear Usuario', ['class' => 'btn btn-primary mt-2']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop