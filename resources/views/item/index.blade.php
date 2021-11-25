@extends('layouts.app')

@section('content')

<div class="my-4 p-4 rounded-3 shadow" style="background-color: white; width:90%;">
    <h1 class="text-center my-2 h4">LIBRO DIARIO</h1>
    <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col" class="fs-5">Fecha</th>
            <th scope="col" class="fs-5">Cuenta</th>
            <th scope="col" class="fs-5">Debe</th>
            <th scope="col" class="fs-5">Haber</th>
          </tr>
        </thead>
        <tbody>
            @php
               $cont = 1;
               $Tdebe = 0;
               $Thaber = 0; 
            @endphp
            @foreach($items as $item)
                <tr>
                    <th>{{$item->date}}</th>
                    <th colspan="3">Partida X{{$cont}}</th>
                </tr>
                @foreach($parts as $part)
                    @if($part->item_id == $item->id)
                        <tr>
                            <td></td>
                            <td>{{$part->account_id}} {{$part->account_title}}</td>
                            <td>{{$part->debit}}</td>
                            <td>{{$part->credit}}</td>
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
            <tr>
                <td></td>
                <td class="text-end"><strong class="me-3">TOTAL :</strong></td>
                <td><Strong>{{$Tdebe}}</Strong></td>
                <td><strong>{{$Thaber}}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<form action="" class="shadow rounded-3 d-flex flex-column align-items-center p-4" style="background-color: white;">
    <h5 class="my-3">Eliga el mes del cual desea obtener el libro diario</h5>
    <select name="" class="selectpicker my-3" data-width="90%" id="">
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

