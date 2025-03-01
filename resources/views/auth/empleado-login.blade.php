@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-form">
        <form method="POST" action="{{ route('empleado.login') }}">
            @csrf
            <div class="form-group">
                <label for="cedula">Cedula:</label>
                <input type="text" id="cedula" name="cedula">
                @error('cedula')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena">
                @error('contrasena')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">Ingresar</button>
            </div>
            <a href="{{ route('empleado.password.email') }}" class="link">¿Olvidaste tu contraseña?</a>
        </form>
    </div>
</div>
@endsection