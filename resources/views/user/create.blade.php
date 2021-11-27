@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5  fs-3">Registrar Usuario</h5>
            <form method="POST" action="{{ route('user.store') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input id="floatingInput" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Nombre">
                    <label for="floatingInput">Nombre</label>
                    @error('name')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input id="floatingInput" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required placeholder="Usuario">
                    <label for="floatingInput">Usuario</label>
                    @error('username')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
{{--                     <input id="floatingInput" type="number" min="0" max="1" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" required placeholder="Tipo">--}}
                    <select class="form-select @error('type') is-invalid @enderror" name="type" id="" value="{{ old('type') }}" required autofocus>
                        <option value="0">Usuario</option>
                        <option value="1">Administrador</option>
                    </select>
                    <label for="floatingInput">Tipo</label>
                    @error('type')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input id="floatingPassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Contraseña" autocomplete="new-password">
                    <label for="floatingPassword">Contraseña</label>
                    @error('password')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror
                </div>
                {{-- <div class="form-floating mb-3">
                    <input type="password" name="password-confirm" class="form-control" id="floatingPassword" placeholder="Confirmar Contraseña" autocomplete="new-password">
                    <label for="floatingPassword">Confirmar Contraseña</label>
                </div> --}}
                <!-- <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                    <label class="form-check-label" for="rememberPasswordCheck">
                    Remember password
                    </label>
                </div> -->
                <div class="d-grid">
                    <button class="btn btn-dark btn-lg fw-bold mt-3" type="submit">Registrar</button>
                </div>
                <!-- <hr class="my-4">
                <div class="d-grid mb-2">
                    <button class="btn btn-google btn-login text-uppercase fw-bold" type="submit">
                    <i class="fab fa-google me-2"></i> Sign in with Google
                    </button>
                </div>
                <div class="d-grid">
                    <button class="btn btn-facebook btn-login text-uppercase fw-bold" type="submit">
                    <i class="fab fa-facebook-f me-2"></i> Sign in with Facebook
                    </button>
                </div> -->
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
