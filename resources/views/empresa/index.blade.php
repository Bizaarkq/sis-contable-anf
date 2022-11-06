@extends('layouts.app')
@section('content')
<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="card mb-3 shadow-sm">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Selecciona tu empresa</h5>
                </div>
                
                <form class="row g-3 needs-validation" method="POST" action="{{ route('empresa.rol') }}">
                  @csrf
                  <div class="col-12">
                    <select name="empresaId" id="empresa" class="form-control" required>
                        @foreach ($empresas as $empresa)
                            <option value="{{$empresa->ID_EMPRESA}}">{{$empresa->NOMBRE_EMPRESA}}</option>
                        @endforeach
                    </select>

                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Seleccionar</button>
                  </div>
                </form>

              </div>
            </div>

          </div>
        </div>
      </div>

    </section>

  </div>
@endsection
@section('js')


@endsection
