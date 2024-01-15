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
        <a href="{{route('admin.users.create')}}" >Crear Usuario</a>
    </div>

    <div class="card-body">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Rut</th>
                    <th>Correo</th>
                    <th colspan="2">ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->rut}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a class="btn btn-secondary btn-sm btn-flat" href="{{route('admin.users.edit',$user)}}"> 
                                Editar
                            </a>
                        </td>
                        <td>
                            <form action="{{route('admin.users.destroy',$user)}}" method="POST">
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