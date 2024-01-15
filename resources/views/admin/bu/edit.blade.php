@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>EDITAR</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::model($bu ,['route' => ['admin.bu.update',$bu],'method' => 'put']) !!}

           @include('admin.bu.partials.form')
            
            {!! Form::submit('Editar BU', ['class' => 'btn btn-primary mt-2']) !!}

        {!! Form::close() !!}
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $(function () {
        $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  
    });
  </script>
@stop