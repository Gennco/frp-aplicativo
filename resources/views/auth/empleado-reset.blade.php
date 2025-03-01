@extends('layouts.app')

@section('content')

<div class="login-container">
    <div class="login-form">
        <form method="POST" action="{{ route('password.update') }}">
        <div class="card-header">{{ __('Restablecer contrasena') }}</div>
            @csrf
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" value="{{ $email ?? old('correo')}}">
                @error('correo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contrasena">Nueva Contraseña:</label>
                <input type="password" id="constrasena" name="contrasena">
                @error('contrasena')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contrasena">Confirmar Contraseña:</label>
                <input type="password" id="contrasena_confirm" name="contrasena_confirmation">
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">Cambiar Contraseña</button>
            </div>
        </form>
    </div>
</div>
@endsection
