@extends('layouts.app')
@section('content')
    <form class="partida shadow rounded-3" action="" method="post" name="item">
        @csrf
        <!-- Default Tabs -->
        <ul class="nav nav-tabs" id="estadosFinan" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="bgTab" data-bs-toggle="tab" data-bs-target="#bg"
                    type="button" role="tab" aria-controls="bg" aria-selected="true">Balance
                    General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="estadoReTab" data-bs-toggle="tab" data-bs-target="#estadoRe"
                    type="button" role="tab" aria-controls="contact" aria-selected="false">Estado de
                    Resultados</button>
            </li>
        </ul>
        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade show active" id="bg" role="tabpanel" aria-labelledby="bgTab">
                
            </div>
            <div class="tab-pane fade" id="estadoRe" role="tabpanel" aria-labelledby="estadoReTab">
                

                <div class="col-lg-6">
                    <div class="row mb-3">
                        <label for="ingresos" class="col-sm-3 col-form-label estado-resultado">Ingresos</label>
                        <div class="col-sm-3">
                            {{-- <input  type="number" step="0.01" id="ingresos" name="33" class="form-control"> --}}
                            
                            <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="">
                                @foreach($accounts as $account)
                                        <option value="{{$account->ID_CATALOGO}}">{{$account->CODIGO_CATALOGO}} {{$account->NOMBRE_CATALOGO_CUENTAS}}</option>  
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
    </form>
@endsection
@section('js')
    <script>

        $(document).ready(function (){
            $('.selectpicker').selectpicker('render')
        });

    </script>
@endsection