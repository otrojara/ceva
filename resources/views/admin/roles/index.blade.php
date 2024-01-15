@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')

    @if (session('info'))
        
    <div class="alert alert-primary" role="alert">
        <strong>¡Éxito!</strong> {{session('info')}}
    </div>
    
    @endif  


    <div class="card">

        <div class="card-header">
            <a href="{{route('admin.roles.create')}}" >Crear Rol</a>
        </div>

        <div class="card-body">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>name</th>
                        <th colspan="2">ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{$role->id}}</td>
                            <td>{{$role->name}}</td>
                            <td>
                                <a class="btn btn-secondary btn-sm btn-flat" href="{{route('admin.roles.edit',$role)}}"> 
                                    Editar
                                </a>
                            </td>
                            <td>
                                <form action="{{route('admin.roles.destroy',$role)}}" method="POST">
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
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop