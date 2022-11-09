@extends('layouts.app')

@section('content')

<form class="partida shadow rounded-3" action="/catalogo/enviar" method="post" name="item">
    @csrf

    <div id="catalogo">

        <div class="row d-flex align justify-content-center rounded border pb-3 px-2 mx-2 my-2 cuenta1">
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="codigoCatalogo1" class="col-form-label">Codigo de cuenta</label>
                <input type="text" name="codigoCatalogo1" class="form-control">
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="nombreCuenta1" class="col-form-label">Nombre de cuenta</label>
                <input type="text" name="nombreCuenta1" class="form-control">
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="codigoPadre1" class="col-form-label">Codigo cuenta padre</label>
                <input type="text" name="codigoPadre1" class="form-control">
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="nivelCuenta1" class="col-form-label">Nivel de cuenta</label>
                <input type="number" min="1" name="nivelCuenta1" class="form-control">
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="tipoCuenta1" class="col-form-label">Tipo</label>
                <select name="tipoCuenta1" class="form-select">
                    <option value="activo">Activo</option>
                    <option value="pasivo">Pasivo</option>
                    <option value="capital">Patrimonio</option>
                    <option value="crd">Resultado deudoras</option>
                    <option value="cra">Resultado acreedoras</option>
                    <option value="cl">Liquidadoras</option>
                  </select>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="saldo1" class="col-form-label">Saldo</label>
                <select name="saldo1" class="form-select">
                    <option value="deudor">Deudor</option>
                    <option value="acreedor">Acreedor</option>
                  </select>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="escorriente1" class="col-form-label">Es corriente?</label>
                <select name="escorriente1" class="form-select">
                    <option value="1">Corriente</option>
                    <option value="0">No corriente</option>
                  </select>
            </div>

            
        </div>

    </div>
    
    <div class="div-botones mt-3">
        <button id="plus" class="btn btn-primary rounded-circle mx-2 shadow btnAdd" type="button"  data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar cuenta"><i class="fas fa-plus"></i></button>
        <button id="check" class="btn btn-success rounded-circle mx-2 shadow btnAdd" type="submit"  data-bs-toggle="tooltip" data-bs-placement="top" title="Guardar catalogo"><i class="fas fa-check"></i></button>
        <button id="minus" class="btn btn-primary rounded-circle mx-2 shadow btnAdd" type="button"  data-bs-toggle="tooltip" data-bs-placement="top" title="Quitar cuenta"><i class="fas fa-minus"></i></button>
    </div>
    <div class="error text-danger text-center mt-3" style="font-weight: 500;"></div>
</form>
@endsection
@section('js')
<script> 

$(document).ready(function (){

    //$('.selectpicker').selectpicker('render')
    let cont = 1

    $("#plus").click(function() {
        cont +=1;
        $("#catalogo").append(`
            <div class="row d-flex align justify-content-center rounded border pb-3 px-2 mx-2 my-2 cuenta${cont}">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                    <label for="codigoCatalogo${cont}" class="col-form-label">Codigo de cuenta</label>
                    <input type="text" name="codigoCatalogo${cont}" class="form-control">
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                    <label for="nombreCuenta${cont}" class="col-form-label">Nombre de cuenta</label>
                    <input type="text" name="nombreCuenta${cont}" class="form-control">
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                    <label for="codigoPadre${cont}" class="col-form-label">Codigo cuenta padre</label>
                    <input type="text" name="codigoPadre${cont}" class="form-control">
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                    <label for="nivelCuenta${cont}" class="col-form-label">Nivel de cuenta</label>
                    <input type="number" min="1" name="nivelCuenta${cont}" class="form-control">
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                    <label for="tipoCuenta${cont}" class="col-form-label">Tipo</label>
                    <select name="tipoCuenta${cont}" class="form-select">
                        <option value="activo">Activo</option>
                        <option value="pasivo">Pasivo</option>
                        <option value="capital">Patrimonio</option>
                        <option value="crd">Resultado deudoras</option>
                        <option value="cra">Resultado acreedoras</option>
                        <option value="cl">Liquidadoras</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                    <label for="saldo${cont}" class="col-form-label">Saldo</label>
                    <select name="saldo${cont}" class="form-select">
                        <option value="deudor">Deudor</option>
                        <option value="acreedor">Acreedor</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-auto">
                <label for="escorriente${cont}" class="col-form-label">Activo</label>
                <select name="escorriente${cont}" class="form-select">
                    <option value="1">Corriente</option>
                    <option value="0">No corriente</option>
                  </select>
            </div>
            </div>
        `);
        //$('.selectpicker').selectpicker('render')
    })

    $("#minus").click(function(){

        if(cont>1){
            $("#catalogo").find(`.cuenta${cont}`).remove();
            cont -=1;
        }
    })

})

</script>
@endsection