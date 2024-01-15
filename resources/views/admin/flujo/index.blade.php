@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Flujo de aprobación</h1>
@stop

@section('content')

<div class="card">
    @if (session('info'))
        
    <div class="alert alert-primary" role="alert">
        <strong>¡Éxito!</strong> {{session('info')}}
    </div>
    
    @endif  
    
    
    <div class="card">
    
        <div class="card-header">
            <a href="{{route('admin.flujo.create')}}" >Crear Flujo</a>
        </div>
    
        <div class="card-body">
            <table id="flujosTable" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>BU</th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($flujoaprobaciones as $FlujoAprobacion)
                        <tr>
                            <td>{{$FlujoAprobacion->id}}</td>
                            <td>{{$FlujoAprobacion->nombre}}</td>
                            <td>
                                
                                @foreach ($bus as $bu)

                                    @if ($bu->flujo_aprobacion_id == $FlujoAprobacion->id)
                                     <small class="badge badge-info">{{$bu->nombre}}</small>
                                    @endif
                                    
                                @endforeach
                                
                            </td>
                            <td>
                                <a class="btn btn-secondary btn-sm btn-flat" href="{{route('flujo',$FlujoAprobacion)}}"> 
                                    Flujo
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-secondary btn-sm btn-flat" href="{{route('admin.flujo.edit',$FlujoAprobacion)}}"> 
                                    Editar
                                </a>

                            </td>
                           
                            <td>
                               
                                
                                
                                <form action="{{route('admin.flujo.destroy',$FlujoAprobacion)}}" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-danger btn-sm btn-flat" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        
                    @empty
                    <tr>
                        <td colspan="6">No hay registros</td>
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

<script src="{{ asset('datatables/datatables.min.js') }}"></script>

<script>
    $(function () {
        $("#flujosTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "asc" ]]
    });
  
    });
  </script>

@stop