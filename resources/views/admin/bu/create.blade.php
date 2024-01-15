@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>CREATE</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'admin.bu.store']) !!}

               @include('admin.bu.partials.form')
                
                {!! Form::submit('Crear Bu', ['class' => 'btn btn-primary mt-2']) !!}

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