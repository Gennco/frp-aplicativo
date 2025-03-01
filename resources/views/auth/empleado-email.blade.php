@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-form">
        <form method="POST" action="{{route('empleado.password.email.send')}}">
            @csrf
            <div class="card-header center-paragraph-bold">Restablecer contraseña</div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                    <input id="correo" type="text" name="correo" value="{{ old('correo') }}" autofocus>
                    @error('correo')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">
                    {{ __('Enviar link de recuperación') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
