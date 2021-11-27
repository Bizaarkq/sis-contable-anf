@extends('layouts.app')
@section('content')

<form class="partida shadow rounded-3" action="/cost" method="POST" name="cost">
    @csrf
    <table class="table">
        <tr>
            <th scope="col">Descripción</th>
            <th scope="col">Monto</th>
            <th scope="col">Tipo</th>
            <th scope="col"></th>
        </tr>
        <tr>
            <td scope="row">
            <input class="descripcion-input mt-2" type="text" name="descripcion">
            </td>
            <td>
                <input class="mt-2" type="number" name="monto" id=""  min="0" step="0.01" placeholder="$00.00">
            </td>
            <td>
                <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="elements">
                <option value="MD">Metetiales Directos</option>  
                <option value="MOD">Mano de Obra Directa</option>  
                <option value="CIF">Costos Indirectos de Frabricacion</option>  
                <option value="CDA">Costos De Administracion</option>  
                </select>
            </td>
            <td>
                <button class="" style="Background-color: transparent; font-size: 2.2rem; color: Dodgerblue; border-style: none;" type="submit"  title="Guardar costo"><i class="fas fa-plus-circle"></i></button>
            </td>
        </tr>
    </table>
    
    <div class="div-botones mt-3">
    </div>
    <div class="error text-danger text-center mt-3" style="font-weight: 500;"></div>
</form>

@if(count($registro))
    <div div class="partida shadow rounded-3 mt-3">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Elemento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registro as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->amount}}</td>
                    <td>{{$item->element}}</td>
                    <td>
                        <form action="/cost/{{$item->id}}" method="POST" style="display: inline-block">
                            @csrf
                            @method('delete')
                            <button class="px-1 py-0 btn text-danger " type="submit" onclick="return confirm('Esta seguro?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
@section('js')


@endsection