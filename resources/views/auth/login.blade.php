@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5  fs-3">Iniciar Sesión</h5>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" id="floatingInput" placeholder="Usuario" required>
                    <label for="floatingInput">Usuario</label>
                    @error('username')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Contraseña" required autocomplete="current-password">
                    <label for="floatingPassword">Contraseña</label>
                    @error('password')
                        <span class="text-danger" role="alert">
                            <p>{{ $message }}</p>
                        </span>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" name="remember" id="rememberPasswordCheck"  {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="rememberPasswordCheck">
                    Recordar contraseña
                    </label>
                </div>
                <div class="d-grid">
                    <button class="btn btn-dark btn-lg fw-bold mt-3" type="submit">Iniciar</button>
                    <div class="form-text text-danger"></div>
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
