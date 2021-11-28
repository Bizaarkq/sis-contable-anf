@extends('layouts.app')

@section('content')
<div class="my-4 p-4 rounded-3 shadow" style="background-color: white; width:90%;" id="mayorizacion">
    <h1 class="text-center h4">LIBRO MAYOR</h1>
    <div class=" row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2 g-lg-3">
        @for ($i = 0; $i < count($ledger); $i++)
            <div class="col">
                <table class="table table-bordered text-center" style="width: 90%; margin: 15px auto;">
                    <thead>
                        <tr class="table-light">
                            <th colspan="2">{{$ledger[$i]['title']}}</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @if($ledger[$i]['cd'] >= $ledger[$i]['cc'])
                            @for ($j = 0; $j < $ledger[$i]['cd']; $j++)
                                <tr>
                                    <td class="position-relative">
                                        <span class="badge rounded-pill bg-dark position-absolute top-center start-0 ms-2">
                                            {{$ledger[$i]['debits'][$j]['item_num']}}
                                        </span>
                                        {{number_format($ledger[$i]['debits'][$j]['mount'],2,".",",")}}
                                    </td>
                                    @if($ledger[$i]['cc'] > $j)
                                        @if (empty($ledger[$i]['credits']))
                                            <td></td>  
                                        @else
                                            <td class="position-relative">
                                                <span class="badge rounded-pill bg-dark position-absolute top-center end-0 me-2">
                                                    {{$ledger[$i]['credits'][$j]['item_num']}}
                                                </span>
                                                {{number_format($ledger[$i]['credits'][$j]['mount'],2,".",",")}}
                                            </td> 
                                        @endif
                                    @else
                                        <td></td>  
                                    @endif
                                </tr> 
                            @endfor
                        @endif
                        @if($ledger[$i]['cc'] >= $ledger[$i]['cd'])
                            @for ($j = 0; $j < $ledger[$i]['cc']; $j++)
                                <tr>
                                    @if($ledger[$i]['cd'] > $j)
                                        @if (empty($ledger[$i]['debits']))
                                            <td></td>  
                                        @else
                                            <td class="position-relative">
                                                <span class="badge rounded-pill bg-dark position-absolute top-center start-0 ms-2">
                                                    {{$ledger[$i]['debits'][$j]['item_num']}}
                                                </span>
                                                {{number_format($ledger[$i]['debits'][$j]['mount'],2,".",",")}}
                                            </td>
                                        @endif
                                    @else
                                        <td></td>  
                                    @endif
                                    <td class="position-relative">
                                        <span class="badge rounded-pill bg-dark position-absolute top-center end-0 me-2">
                                            {{$ledger[$i]['credits'][$j]['item_num']}}
                                        </span>
                                        {{number_format($ledger[$i]['credits'][$j]['mount'],2,".",",")}}
                                    </td> 
                                    
                                </tr> 
                            @endfor
                        @endif  
                    </tbody>    
                    <tfoot>
                        <tr>
                            @if ($ledger[$i]['totaldebits'] == 0)
                                <td style="color: white;">---</td>
                                <td>{{number_format($ledger[$i]['totalcredits'],2,".",",")}}</td>
                            @endif
                            @if ($ledger[$i]['totalcredits'] == 0)
                                <td>{{number_format($ledger[$i]['totaldebits'],2,".",",")}}</td>
                                <td style="color: white;">---</td>    
                            @endif
                            @if ($ledger[$i]['totaldebits'] != 0 && $ledger[$i]['totalcredits'] != 0)
                                <td>{{number_format($ledger[$i]['totaldebits'],2,".",",")}}</td>
                                <td>{{number_format($ledger[$i]['totalcredits'],2,".",",")}}</td>
                            @endif
                        </tr>
                        <tr class="table-light">
                            @if($ledger[$i]['totaldebits'] > $ledger[$i]['totalcredits'])
                                <td>{{number_format($ledger[$i]['total'],2,".",",")}}</td>
                                <td></td>
                            @else
                                <td></td>
                                <td>{{number_format($ledger[$i]['total'],2,".",",")}}</td>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endfor
        <div class="col">
            <table class="table table-bordered text-center" style="width: 90%; margin: 15px auto;">
                <thead>
                    <tr class="table-light">
                        <th colspan="2">{{$adjustment['title']}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{number_format($adjustment['fiscalcredit'],2,".",",")}}</td>
                        <td>{{number_format($adjustment['fiscaldebit'],2,".",",")}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        @if($adjustment['balance']==1)
                            <td>{{number_format($adjustment['total'],2,".",",")}}</td>
                            <td></td>
                        @else
                        <td></td> 
                        <td>{{number_format($adjustment['total'],2,".",",")}}</td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="my-4 p-4 rounded-3 shadow" style="background-color: white; width:90%;" id="mayorizacion">
    <h1 class="text-center h4">BALANCE DE COMPROBACIÓN</h1>
    <table class="table table-bordered mt-4 mx-auto" style="width:90%;">
        <thead>
            <tr class="table-light text-center">
                <th>RUBROS</th>
                <th>DEBE</th>
                <th>HABER</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3" class="table-light">ACTIVO</th>
            </tr>
            @if(empty($checkingBalance['asset']))
                <tr>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                </tr>
            @else
                @foreach ($checkingBalance['asset'] as $asset)
                <tr>
                    <td class="px-4">{{$asset['title']}}</td>
                    @if ($asset['balance']==1)
                        <td class="text-center">{{number_format($asset['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($asset['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">PASIVO</th>
            </tr>
            @if(empty($checkingBalance['liability']))
                <tr>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                </tr>
            @else
                @foreach ($checkingBalance['liability'] as $liability)
                <tr>
                    <td class="px-4">{{$liability['title']}}</td>
                    @if ($liability['balance']==1)
                        <td class="text-center">{{number_format($liability['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($liability['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">CAPITAL</th>
            </tr>
            @if(empty($checkingBalance['capital']))
                <tr>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                </tr>
            @else
                @foreach ($checkingBalance['capital'] as $capital)
                <tr>
                    <td class="px-4">{{$capital['title']}}</td>
                    @if ($capital['balance']==1)
                        <td class="text-center">{{number_format($capital['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($capital['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">INGRESOS</th>
            </tr>
            @if(empty($checkingBalance['earnings']))
                <tr>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                </tr>
            @else
                @foreach ($checkingBalance['earnings'] as $earning)
                <tr>
                    <td class="px-4">{{$earning['title']}}</td>
                    @if ($earning['balance']==1)
                        <td class="text-center">{{number_format($earning['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($earning['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">COSTOS</th>
            </tr>
            @if(empty($checkingBalance['costs']))
                <tr>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                </tr>
            @else
                @foreach ($checkingBalance['costs'] as $cost)
                <tr>
                    <td class="px-4">{{$cost['title']}}</td>
                    @if ($cost['balance']==1)
                        <td class="text-center">{{number_format($cost['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($cost['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">GASTOS</th>
            </tr>
            @if(empty($checkingBalance['expenses']))
                <tr>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                    <td style="color: white;">---</td>
                </tr>
            @else
                @foreach ($checkingBalance['expenses'] as $expense)
                <tr>
                    <td class="px-4">{{$expense['title']}}</td>
                    @if ($expense['balance']==1)
                        <td class="text-center">{{number_format($expense['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($expense['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="table-light text-center">
                <td class="text-end"><strong class="me-3">TOTAL :</strong></td>
                <td><strong>{{number_format($checkingBalance['totaldebit'],2,".",",")}}</strong></td>
                <td><strong>{{number_format($checkingBalance['totalcredit'],2,".",",")}}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="my-4 p-4 rounded-3 shadow" style="background-color: white; width:90%;" id="mayorizacion">
    <h1 class="text-center h4">ESTADO DE RESULTADOS</h1>
    <table class="table table-bordered mt-4 mx-auto"  style="width:80%;">
        <tr>
            <td>INGRESOS</td>
            <td class="text-center">{{number_format($statementOfIncome['earnings'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(-) COSTO DE VENTA</td>
            <td class="text-center">{{number_format($statementOfIncome['costs'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(=) UTILIDAD BRUTA</td>
            <td class="text-center">{{number_format($statementOfIncome['grossprofit'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(-) GASTOS DE OPERACIÓN</td>
            <td class="text-center">{{number_format($statementOfIncome['operationcosts'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(=) UTILIDAD ANTES DE OPERACIÓN</td>
            <td class="text-center">{{number_format($statementOfIncome['profitbeforeoperation'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(-) RESERVA LEGAL</td>
            <td class="text-center">{{number_format($statementOfIncome['legalreserve'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(=) UTILIDAD ANTES DE IMPUESTOS</td>
            <td class="text-center">{{number_format($statementOfIncome['profitbeforetaxes'],2,".",",")}}</td>
        </tr>
        <tr>
            <td>(-) IMPUESTO SOBRE LA RENTA</td>
            <td class="text-center">{{number_format($statementOfIncome['incometax'],2,".",",")}}</td>
        </tr>
        <tr class="bg-light">
            <td>(=) UTILIDAD BRUTA</td>
            <td class="text-center">{{number_format($statementOfIncome['netprofit'],2,".",",")}}</td>
        </tr>
    </table>
</div>
<div class="my-4 p-4 rounded-3 shadow" style="background-color: white; width:90%;" id="mayorizacion">
    <h1 class="text-center h4">BALANCE GENERAL</h1>
    <table class="table table-bordered mt-4 mx-auto" style="width:90%;">
        <thead>
            <tr class="table-light text-center">
                <th>RUBROS</th>
                <th>DEBE</th>
                <th>HABER</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3" class="table-light">ACTIVO</th>
            </tr>
            @if(empty($balanceSheet['asset']))
                <td style="color: white;">---</td>
                <td style="color: white;">---</td>
                <td style="color: white;">---</td>
            @else
                @foreach ($balanceSheet['asset'] as $asset)
                <tr>
                    <td class="px-4">{{$asset['title']}}</td>
                    @if ($asset['balance']==1)
                        <td class="text-center">{{number_format($asset['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($asset['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">PASIVO</th>
            </tr>
            @if(empty($balanceSheet['liability']))
                <td style="color: white;">---</td>
                <td style="color: white;">---</td>
                <td style="color: white;">---</td>
            @else
                @foreach ($balanceSheet['liability'] as $liability)
                <tr>
                    <td class="px-4">{{$liability['title']}}</td>
                    @if ($liability['balance']==1)
                        <td class="text-center">{{number_format($liability['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($liability['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
            <tr>
                <th colspan="3" class="table-light">CAPITAL</th>
            </tr>
            @if(empty($balanceSheet['capital']))
                <td style="color: white;">---</td>
                <td style="color: white;">---</td>
                <td style="color: white;">---</td>
            @else
                @foreach ($balanceSheet['capital'] as $capital)
                <tr>
                    <td class="px-4">{{$capital['title']}}</td>
                    @if ($capital['balance']==1)
                        <td class="text-center">{{number_format($capital['total'],2,".",",")}}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td class="text-center">{{number_format($capital['total'],2,".",",")}}</td>
                    @endif
                </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="table-light text-center">
                <td class="text-end"><strong class="me-3">TOTAL :</strong></td>
                <td><strong>{{number_format($balanceSheet['totaldebit'],2,".",",")}}</strong></td>
                <td><strong>{{number_format($balanceSheet['totalcredit'],2,".",",")}}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<div>
    <a href="/item/{{$selectedmonth}}/pdf" class="btn btn-primary shadow"><strong>Generar Reporte en PDF</strong></a>
</div>
@endsection
@section('js')
@endsection