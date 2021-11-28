@extends('layouts.app')
@section('content')

<div class="partida shadow rounded-3">
    <form class="d-flex flex-row w-100" action="costo.resultados" method="post">
        @csrf
        <div class="w-25 me-4">
            <p class="fw-bold text-center">Porcentaje de utilidad</p>
            <div class="input-group mb-3">
                <input type="number" class="form-control" name="porcentaje" aria-label="Dollar amount (with dot and two decimal places)">
                <span class="input-group-text">%</span>
            </div>  
            <p class="fw-bold text-center">Cantidad Producida</p>
            <div class="input-group mb-3">
                <input type="number" class="form-control" name="cantidad" aria-label="Dollar amount (with dot and two decimal places)">
                
            </div>  
            <button type="submit" class="btn btn-outline-info w-100">Generar</button>
        </div>
        <table class="table table-dark table-striped border-info">
            <tbody>
                <tr>
                    <th scope="row">MD - Materiales directos</th>
                    <td>${{$MD}}</td>
                </tr>
                <tr>
                    <th scope="row">MOD - Mano de obra directa</th>
                    <td>${{$MOD}}</td>
                </tr>
                <tr>
                    <th scope="row">CIF - Costos indirectos de fabricacion</th>
                    <td>${{$CIF}}</td>
                </tr>
                <tr>
                    <th scope="row">CTP - Costos de produccion</th>
                    <td>${{$CTP}}</td>
                </tr>
                <tr>
                    <th scope="row">CP -  Costos primos</th>
                    <td>${{$CP}}</td>
                </tr>
                <tr>
                    <th scope="row">CC - Costos de conversion</th>
                    <td>${{$CC}}</td>
                </tr>
                <tr>
                    <th scope="row">Costos de perido</th>
                    <td>${{$costosPeriodo}}</td>
                </tr>
                <tr>
                    <th scope="row">Porcentaje de utilidad</th>
                    <td>{{$porciento}}%</td>
                </tr>
                <tr>
                    <th scope="row">Cantidad producida</th>
                    <td>{{$producido}}</td>
                </tr>
                <tr>
                    <th scope="row">Costo unitario</th>
                    <td>${{$costoUni}}</td>
                </tr>
                <tr>
                    <th scope="row">Precio de venta</th>
                    <td>${{$precioVenta}}</td>
                </tr>
                <tr>
                    <th scope="row">Precio de venta + IVA</th>
                    <td>${{$precioIva}}</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

@endsection
@section('js')


@endsection