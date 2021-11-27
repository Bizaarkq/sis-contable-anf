@extends('layouts.app')
@section('content')

<div class="partida shadow rounded-3">
    <div class="d-flex flex-row w-100">
        <div class="w-25 me-4">
            <p class="fw-bold text-center">Porcentaje de utilidad</p>
            <div class="input-group mb-3">
                <input type="number" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                <span class="input-group-text">%</span>
            </div>  
            <button type="button" class="btn btn-outline-info w-100">Generar</button>
        </div>
        <table class="table table-dark table-striped border-info">
            <tbody>
                <tr>
                    <th scope="row">MD - Materiales directos</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">MOD - Mano de obra directa</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">CIF - Costos indirectos de fabricacion</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">CTP - Costos de produccion</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">CP -  Costos primos</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">CC - Costos de conversion</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">Costos de perido</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">Porcentaje de utilidad</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">Costo unitario</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">Precio de venta</th>
                    <td>?</td>
                </tr>
                <tr>
                    <th scope="row">Precio de vanta + IVA</th>
                    <td>?</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
@section('js')


@endsection