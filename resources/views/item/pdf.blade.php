<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html{
            font-family: sans-serif;
        }   

        table {
            caption-side: bottom;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            text-align: inherit;
            text-align: -webkit-match-parent;
        }

        thead,
        tbody,
        tfoot,
        tr,
        td,
        th {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }

        .table-bordered  > * {
            border-width: 1px 1px;
        }

        div{
            width: 100%;
        }

        td,th{
            padding: 4px;
            border: 1pt solid #aaaeb3;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #aaaeb3;
        }

        h3{
            text-align: center;
        }

        thead,
        tbody,
        tfoot{
            border-bottom: 2pt solid #000000;
        }
        
    </style>        
</head>
<body>
    <div class="" style="background-color: white; width:90%; margin:0 auto;">
        <h4 class="" style="text-align: center;font-size:20px;">LIBRO DIARIO</h4>
        <table class="table table-bordered" style="page-break-after:always;">
            <thead>
              <tr style="background-color: #eee">
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
                    <tr style="background-color: #eee">
                        <th>{{$item->date}}</th>
                        <th colspan="3" >PARTIDA X{{$cont}}</th>
                    </tr>
                    @foreach($parts as $part)
                        @if($part->item_id == $item->id)
                            <tr>
                                <td></td>
                                <td>{{$part->account_id}} {{$part->account_title}}</td>
                                @if($part->debit == 0)
                                <td></td>
                                @else
                                <td>$ {{$part->debit}}</td>
                                @endif
                                @if($part->credit == 0)
                                <td></td>
                                @else
                                <td>$ {{$part->credit}}</td>
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
                <tr style="background-color: #eee">
                    <td></td>
                    <td class="text-end"><strong class="me-3">TOTAL :</strong></td>
                    <td><Strong>$ {{$Tdebe}}</Strong></td>
                    <td><strong>$ {{$Thaber}}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="" style="background-color: white; width:90%; margin:0 auto;">
        <h4 class="" style="text-align: center;font-size:20px;">LIBRO MAYOR</h4>
        <div class="">
            @for ($i = 0; $i < count($ledger); $i++)
                <div class="">
                    <table class="table table-bordered text-center" style="width: 60%; margin: 15px auto;">
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
                <table class="table table-bordered text-center" style="width: 60%; margin: 15px auto;">
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

</body>
</html>