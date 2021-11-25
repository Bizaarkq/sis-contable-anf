@extends('layouts.app')

@section('content')

<div class="my-4 p-4 rounded-3 shadow" style="background-color: white; width:90%;">
    @if(count($items) < 1)
        <p  class="h3">No hay registros del mes seleccionado</p>
    @else
    <h1 class="text-center my-2 h4">LIBRO DIARIO</h1>
    <table class="mt-3 table table-bordered">
        <thead class="bg-light">
          <tr>
            <th scope="col">FECHA</th>
            <th scope="col">CUENTA</th>
            <th scope="col">DEBE</th>
            <th scope="col">HABER</th>
          </tr>
        </thead>
        <tbody>
            @php
               $cont = 1;
               $Tdebe = 0;
               $Thaber = 0; 
            @endphp
            @foreach($items as $item)
                <tr class="bg-light">
                    <th>{{$item->date}}</th>
                    <th colspan="3">PARTIDA X{{$cont}}</th>
                </tr>
                @foreach($parts as $part)
                    @if($part->item_id == $item->id)
                        <tr>
                            <td></td>
                            <td>{{$part->account_id}} {{$part->account_title}}</td>
                            @if($part->debit == 0)
                            <td></td>
                            @else
                            <td>$ {{number_format($part->debit,2,".",",")}}</td>
                            @endif
                            @if($part->credit == 0)
                            <td></td>
                            @else
                            <td>$ {{number_format($part->credit,2,".",",")}}</td>
                            @endif
                        </tr>
                        @php($Tdebe += $part->debit)
                        @php($Thaber += $part->credit)
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td colspan="3"><i>{{$item->description}}</i></td>
                </tr>
                @php($cont++)
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <td></td>
                <td class="text-end"><strong class="me-3">TOTAL :</strong></td>
                <td><Strong>$ {{number_format($Tdebe,2,".",",")}}</Strong></td>
                <td><strong>$ {{number_format($Thaber,2,".",",")}}</strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-end">
        <a class="btn btn-primary m-3" href="/item/{{$selectedmonth}}/allDocuments" target="_blank">Ver todos los Documentos Contables</a>
    </div>
    @endif
</div>
<form action="/item/setMonthJournalBook" method="post" class="shadow rounded-3 d-flex flex-column align-items-center p-4" style="background-color: white;">
    @csrf
    <h5 class="my-3">Eliga el mes del cual desea obtener el libro diario</h5>
    <select name="month" class="selectpicker my-3" data-width="90%" id="">
        @for ($i = 0; $i < $currentmonth; $i++)
        <option value="{{$i+1}}">{{$months[$i]}}</option> 
        @endfor
    </select>
    <button class="btn btn-primary" type="submit">Obtener</button>
</form>
{{-- <form action="/item/pdf" method="">
    @csrf
    @method('get')
    <button type="submit" class="btn btn-primary shadow"><strong>Generar PDF</strong></button>
</div> --}}

@endsection
@section('js')
<script>
    $('.selectpicker').selectpicker('render')
</script>
@endsection

