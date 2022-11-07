@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card my-3 p-2 p-md-5 shadow">
        <table class="table table-bordered table-responsive display nowrap" id="users" class="display"  cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th>Descripcion</th>
                    <th>Registrado para</th>
                    <th>Creado por</th>
                    <th>Actualizado por</th>
                    <th>Creado</th>
                    <th>Actualizado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{$item->DESCRIPCION_PARTIDA}}</td>
                        <td>{{$item->FECHA_PARTIDA}}</td>
                        <td>{{$item->CREATED_USER}}</td>
                        <td>{{$item->UPDATED_USER}}</td>
                        <td>{{$item->CREATED_AT}}</td>
                        <td>{{$item->UPDATED_AT}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Descripcion</th>
                    <th>Registrado para</th>
                    <th>Creado por</th>
                    <th>Actualizado por</th>
                    <th>Creado</th>
                    <th>Actualizado</th>
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