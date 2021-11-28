@extends('layouts.app')
@section('content')

<form class="partida shadow rounded-3" action="{{route('cost.update', $costoEdit)}}" method="post" name="cost">
    @csrf
    @method('put')
    <div class="alert alert-warning" role="alert">
        Edicion de costo con ID: {{$costoEdit->id}}
    </div>
    <table class="table">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Descripción</th>
            <th scope="col">Monto</th>
            <th scope="col">Tipo</th>
            <th scope="col"></th>
        </tr>
        <tr>
            <td scope="row">
            <input class="descripcion-input mt-2" type="text" name="idEdit" value="{{$costoEdit->id}}" readonly>
            </td>
            <td scope="row">
            <input class="descripcion-input mt-2" type="text" name="descripcionEdit" value="{{$costoEdit->description}}">
            </td>
            <td>
                <input class="mt-2" type="number" name="montoEdit" id=""  min="0" step="0.01" value="{{$costoEdit->amount}}">
            </td>
            <td>
                <p id="element" hidden>{{$costoEdit->element}}</p>
                <select id="opciones" class="selectpicker my-2" data-live-search="true" data-width="100%" name="elementEdit">
                <option value="MD">Materiales Directos</option>  
                <option value="MOD">Mano de Obra Directa</option>  
                <option value="CIF">Costos Indirectos de Frabricacion</option>  
                <option value="CDA">Costos de ventas generales y administrativos</option>  
                </select>
            </td>
            <td>
                <button style="Background-color: transparent; font-size: 2.2rem; color: Dodgerblue; border-style: none;" type="submit"  title="Actualizar costo"><i class="fas fa-sync"></i></button>
            </td>
        </tr>
    </table>
</form>

<script>
    var elemt = document.getElementById("element").textContent 
    var opc = document.getElementById("opciones")

    opc.value = elemt
</script>

@endsection
@section('js')
@endsection