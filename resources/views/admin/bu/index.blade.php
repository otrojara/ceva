@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Bu</h1>
@stop

@section('content')
@if (session('info'))
        
<div class="alert alert-primary" role="alert">
    <strong>¡Éxito!</strong> {{session('info')}}
</div>

@endif  


<div class="card">

    <div class="card-header">
        <a href="{{route('admin.bu.create')}}" >Crear BU</a>
    </div>

    <div class="card-body">
        <table id="usersTable" class="table table-striped table-sm">
            
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>nombre</th>
                    <th>Grupo Geovictoria</th>
                    <th>Zona</th>
                    <th ></th>
                    <th ></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bus as $bu)
                    <tr>
                        <td>{{$bu->id}}</td>
                        <td>{{$bu->codigo}}</td>
                        <td>{{$bu->nombre}}</td>
                        <td>{{$bu->grupo_geovictoria}}</td>
                        <td>{{$bu->zona}}</td>
                        <td>
                            <a class="btn btn-secondary btn-sm btn-flat" href="{{route('admin.bu.edit',$bu)}}"> 
                                Editar
                            </a>
                        </td>
                        <td>
                            <form action="{{route('admin.bu.destroy',$bu)}}" method="POST">
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
    <link rel="stylesheet" href="/css/admin_custom.css">
    

    <link href="datatables/datatables.min.css" rel="stylesheet">
@stop

@section('js')

<script src="{{ asset('datatables/datatables.min.js') }}"></script>

<script>
    $(function () {
        $("#usersTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "asc" ]]
    });
  
    });
  </script>

@stop