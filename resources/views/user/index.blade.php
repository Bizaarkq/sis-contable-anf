@extends('layouts.app')
@section('content')
<div class="container">
    <a class="btn btn-dark shadow" href="/user/create" >Agregar Usuario</a>
    <div class="card my-3 p-2 p-md-5 shadow">
        <table class="table table-bordered table-responsive display nowrap" id="users" class="display"  cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->username}}</td>
                        <td>
                            @if ($user->type == 1)
                                Administrador
                            @else
                                Usuario
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                                </button>
                                <ul class="dropdown-menu" style="min-width:5rem;">
                                    <form action="/user/activeUpdate/{{$user->id}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @if ($user->active == 1)
                                            <button class="dropdown-item text-danger" type="submit" onclick="return confirm('Esta seguro de desactivar este usuario?')">Desactivar</button>
                                        @else
                                            <button class="dropdown-item text-danger" type="submit" onclick="return confirm('Esta seguro de activar este usuario?')">Activar</button>    
                                        @endif
                                    </form>
                                    <form action="/user/roleUpdate/{{$user->id}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @if ($user->type == 1)
                                            <button class="dropdown-item text-warning" type="submit" onclick="return confirm('Esta seguro de degradarlo a usuario?')">Degradar</button>
                                        @else
                                            <button class="dropdown-item text-warning" type="submit" onclick="return confirm('Esta seguro de promoverlo a administrador?')">Promover</button>    
                                        @endif
                                    </form>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready( function () {
        $('#users').DataTable({
            responsive: true,
        })
    })	
</script>
@endsection