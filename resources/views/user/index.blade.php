@extends('layouts.app')
@section('content')
<div class="container">
    <a class="btn btn-dark" href="/register" >Agregar Usuario</a>
    <div class="card my-3 p-2 p-md-5">
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
                                    {{-- <li><a class="dropdown-item text-primary" href="user/{{$user->id}}"><i class="far fa-eye me-1"></i>Details</a></li> --}}
                                    <form action="/user/roleUpdate/{{$user->id}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @if ($user->type == 1)
                                            <button class="dropdown-item text-warning" type="submit" onclick="return confirm('Are you sure to demote the admin?')">Degradar</button>
                                        @else
                                            <button class="dropdown-item text-warning" type="submit" onclick="return confirm('Are you sure to promote the user?')">Promover</button>    
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