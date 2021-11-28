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
                        <td>{{$item->description}}</td>
                        <td>{{$item->date}}</td>
                        <td>{{$item->created_by}}</td>
                        <td>{{$item->updated_by}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->updated_at}}</td>
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