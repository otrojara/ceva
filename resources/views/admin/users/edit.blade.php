@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>EDITAR</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::model($user ,['route' => ['admin.users.update',$user],'method' => 'put']) !!}

           @include('admin.users.partials.form')
            
            {!! Form::submit('Editar Usuarios', ['class' => 'btn btn-primary mt-2']) !!}

        {!! Form::close() !!}
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')



@stop