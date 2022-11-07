@extends('layouts.app')

@section('content')

<form class="partida shadow rounded-3" action="/item" method="post" name="item">
    @csrf
    <table class="" id="table">
        <tr class="">
            <th class="fecha"></th>
            <th class="concepto">Cuenta</th>
            <th class="debe">Debe</th>
            <th class="haber">Haber</th>
        </tr>
        <tr>
            <td class="fecha">
            <input  class="" type="date" name="" id=""  value="{{$date}}" readonly>
            </td>
            <td class="concepto">
                <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="account1">
                    @foreach($accounts as $account)
                            <option value="{{$account->ID_CATALOGO}}">{{$account->CODIGO_CATALOGO}} {{$account->NOMBRE_CATALOGO_CUENTAS}}</option>  
                    @endforeach
                </select>
            </td>
            <td class="debe">
                <input class="debe-input" type="number" name="debe1" id=""  min="0" step="0.01">
            </td>
            <td class="haber">
                <input class="haber-input" type="number" name="haber1" id=""  min="0" step="0.01">
            </td>
        </tr>
        <tr>
            <td class="fecha">
            <input  class="input-fecha" type="date" name="" id="" style="color: #f8f9fa;" readonly>
            </td>
            <td class="concepto">
                <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="account2">
                    @foreach($accounts as $account)
                    <option value="{{$account->ID_CATALOGO}}">{{$account->CODIGO_CATALOGO}} {{$account->NOMBRE_CATALOGO_CUENTAS}}</option>  
                    @endforeach
                </select>
            </td>
            <td class="debe">
                <input class="debe-input" type="number" name="debe2" id=""  min="0" step="0.01">
            </td>
            <td class="haber">
                <input class="haber-input" type="number" name="haber2" id=""  min="0" step="0.01">
            </td>
        </tr>
    </table>
    <table id="table">
        <tr>
            <td class="descripcion">
                <input type="text" name="description" class="input-descripcion" placeholder="Descripción de la partida">
            </td>
        </tr>
        <tr>
            <td class="fecha invisible">
            <input  class="" type="date" name="" id="" readonly>
            </td>
            <td class="concepto invisible">
               
            </td>
            <td class="totales">
                <input class="text-success" type="number" name="" id="total-debe"  min="0" value="0" step="0.01" readonly>
            </td>
            <td class="totales">
                <input class="text-success" type="number" name="" id="total-haber"  min="0" value="0" step="0.01" readonly>
            </td>
        </tr>
    </table>
    <div class="div-botones mt-3">
        <button id="plus" class="btn btn-primary rounded-circle mx-2 shadow btnAdd" type="button"  data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar cuenta"><i class="fas fa-plus"></i></button>
        <button id="check" class="btn btn-success rounded-circle mx-2 shadow btnAdd" type="submit"  data-bs-toggle="tooltip" data-bs-placement="top" title="Guardar partida"><i class="fas fa-check"></i></button>
        <button id="minus" class="btn btn-primary rounded-circle mx-2 shadow btnAdd" type="button"  data-bs-toggle="tooltip" data-bs-placement="top" title="Quitar cuenta"><i class="fas fa-minus"></i></button>
    </div>
    <div class="error text-danger text-center mt-3" style="font-weight: 500;"></div>
</form>
@endsection
@section('js')
<script> 

$(document).ready(function (){

    $('.selectpicker').selectpicker('render')
    let cont = 2

    $("#plus").click(function() {
        cont +=1
        $('#table').append(`
            <tr>
                <td class="fecha">
                <input  class="input-fecha" type="date" name="" id=""  style="color: #f8f9fa;" readonly>
                </td>
                <td class="concepto">
                    <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="account${cont}">
                        @foreach($accounts as $account)
                                <option value="{{$account->ID_CATALOGO}}">{{$account->CODIGO_CATALOGO}} {{$account->NOMBRE_CATALOGO_CUENTAS}}</option>  
                        @endforeach
                    </select>
                </td>
                <td class="debe">
                    <input class="debe-input" type="number" name="debe${cont}" id=""  min="0"  step="0.01">
                </td>
                <td class="haber">
                    <input class="haber-input" type="number" name="haber${cont}" id=""  min="0"  step="0.01">
                </td>
            </tr>     
        `)

        $('.selectpicker').selectpicker('render')
        
    })

    $("#minus").click(function(){

        if(cont>2){
            let n = $("#table").find("tr:last").find(".debe-input").val()
            let n1 = $("#table").find("tr:last").find(".haber-input").val()

            if($('#total-debe').val() > 0){
                $('#total-debe').val(parseFloat($('#total-debe').val())-parseFloat("0"+n))
            }

            if($('#total-haber').val() > 0){
                $('#total-haber').val(parseFloat($('#total-haber').val())-parseFloat("0"+n1))
            }
            correctbalance()
            $("#table").find("tr:last").remove()
            cont -=1;
        }
    })

    $(document).on('change','.debe-input', function(){ 
        let suma = 0
        $(".debe-input").each(function() {
            suma += parseFloat("0"+$(this).val())
        })
        $('#total-debe').val(suma)
        correctbalance()
    }).change()

    $(document).on('change','.haber-input', function(){
        let suma = 0
        $(".haber-input").each(function() {
            suma += parseFloat("0"+$(this).val())
        })
        $('#total-haber').val(suma)
        correctbalance()
    }).change()
    
    function correctbalance(){
        let totaldebe = $('#total-debe').val()
        let totalhaber = $('#total-haber').val()
        if( totaldebe != totalhaber){
            $('#total-debe').addClass("text-danger")
            $('#total-debe').removeClass("tex-success")
            $('#total-haber').addClass("text-danger")
            $('#total-haber').removeClass("tex-success")
        }
        else{
            $('#total-debe').addClass("text-success")
            $('#total-debe').removeClass("text-danger")
            $('#total-haber').addClass("tex-success")
            $('#total-haber').removeClass("text-danger")
        }
    }

    $("form").submit(function(event) {

        for (let i = 1; i <= cont; i++) {
            let debe = parseFloat($(`input[name=debe${i}]`).val())
            let haber = parseFloat($(`input[name=haber${i}]`).val())  
            if (Number.isNaN(debe)) {
                $(`input[name=debe${i}]`).val(0)
            }
            if (Number.isNaN(haber)) {
                $(`input[name=haber${i}]`).val(0)
            }

            if(debe > 0 && haber > 0){
                $('.error').text('Cada cuenta debe tener un valor en debe o haber, no en ambos')
                event.preventDefault()
            }
        }

        let totaldebe = $('#total-debe').val()
        let totalhaber = $('#total-haber').val()
        let descripcion = $('.input-descripcion').val()
        if(totaldebe != totalhaber){
            $('.error').text('La partida debe estar en balance')
            event.preventDefault()
        }
        else if(totaldebe == 0 || totalhaber == 0){
            $('.error').text('El balance de la partida debe ser mayor a cero')
            event.preventDefault()
        }
        else if(descripcion.length < 1){
            $('.error').text('La descripcion no puede estar vacia')
            event.preventDefault()    
        }
        else{
            return
        }
    })
    

})

</script>
@endsection


{{-- <div class="radios">
    <div class="form-check mx-2">
        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
        <label class="form-check-label" for="flexRadioDefault1">Debe</label>
    </div>
    <div class="form-check mx-2">
        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
        <label class="form-check-label" for="flexRadioDefault2">Haber</label>
    </div>
</div> --}}

{{-- $(".debe-input").change(function () {
    let suma = 0
    $(".debe-input").each(function() {
        suma += parseFloat($(this).val())
    })
    console.log(suma)
}).change()  --}}