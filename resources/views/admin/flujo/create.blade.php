@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Flujo de aprobación</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.flujo.store']) !!}

           @include('admin.flujo.partials.form')
            
            {!! Form::submit('Crear Flujo', ['class' => 'btn btn-primary mt-2']) !!}

        {!! Form::close() !!}
    </div>
</div>

@stop

@section('css')
   
@stop

@section('js')


@stop