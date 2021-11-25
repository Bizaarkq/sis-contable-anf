@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100">
    <div class="row d-flex justify-content-center mt-5">
        <div class="col-12 d-flex justify-content-center">
            <h1 class="mt-5 mb-3 display-1">Bienvenido</h1>
        </div>
        <div class="col-12 col-md-4 col-lg-3 col-xxl-2 d-flex justify-content-center">
            <a type="submit" class="btn btn-primary mt-5 p-4 border-0 shadow rounded-3" href="item/create">
                <h5 class="fs-3">Iniciar partida</h5>
                <i class="fas fa-folder-plus mt-2" style="font-size:50px;"></i>
            </a>
        </div>
        {{-- <div class="col-12 col-md-4 col-lg-3 col-xxl-2 d-flex justify-content-center">
            <a type="submit" class="btn btn-primary mt-5 p-4 border-0 shadow rounded-" href="">
                <h5 class="fs-3">Cargar partida</h5>
                <i class="fas fa-folder-open mt-2" style="font-size:50px;"></i>
            </a>
        </div> --}}
    </div>
</div>
@endsection
