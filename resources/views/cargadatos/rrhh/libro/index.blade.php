@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
@if (session('info'))
        
<div class="alert alert-primary" role="alert">
    <strong>¡Éxito!</strong> {{session('info')}}
</div>

@endif  


<div class="card">

    <div class="card-header">
        <a href="{{route('cargadatos.libro.create')}}" >Cargar Libro</a>
    </div>

    
</div>


<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form id="formImportLibro"  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
            
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="file" accept=".xlsx" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer text-right">
                    <a href="/FormatoProyecciones" class="btn btn-success btn-flat btn-sm" ><i class="fas fa-file-excel"></i></a>
                  <button type="submit" id="BtnImport" class="btn btn-flat btn-sm  btn-primary">Cargar</button>
                </div>
            
              </form>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{ asset('js/cargadatos.js') }}"></script>
@stop