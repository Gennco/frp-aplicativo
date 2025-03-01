@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view section-center">
    <div class="col-md-8">
        <div class="card"> 
            <div class="card-header text-center">Encuesta tipo {{ strtoupper(session('tipo')) }} - Error</div>
            <div class="card-body">
                <div class="d-flex justify-content-center flex-column text-center">
                        <i class="fas fa-exclamation-triangle mb-3" style="font-size: 50px;"></i>
                        
                        <p class="fw-bold mb-3">¡ADVERTENCIA!</p>
                        
                        <p class="mb-4">Esta sección no existe o usted no está autorizado para acceder.</p>
                        
                        <a href="{{ route('encuesta.preguntas', ['tipo'=> session('tipo'), 'seccion'=> session('ruta')]) }}" class="submit-btn">Volver</a>
                </div>    
            </div>
        </div>     
    </div>
</div>
@endsection
