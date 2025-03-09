@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view">
<div class="col-md-12">
        <div class="card">
            <div class="card-header center-paragraph-bold">Encuesta tipo {{strtoupper($seccion->tipo)}} - {{$seccion->nombre}}
                    @php
                        $total = $total ?? 100; 
                        $avance = $avance ?? 0;
                    @endphp
                 <!-- Barra de progreso -->
                    <!-- Barra de progreso mejorada -->
                @if($total > 0)
                <div style="padding: 15px 20px; display: flex; align-items: center; gap: 10px; background: #f8f9fa;">
                    <!-- Contenedor personalizado para la barra -->
                    <div style="flex-grow: 1; height: 10px; background: #d6dde5; border-radius: 5px; overflow: hidden; position: relative;">
                        <div class="progress-bar" 
                            style="width: {{ ($avance / $total) * 100 }}%;
                                    height: 100%;
                                    background: #0D47A1;
                                    transition: width 0.5s ease-in-out;
                                    border-radius: 5px;">
                        </div>
                    </div>
                    <small style="color: #3b4a6b; font-weight: 500; white-space: nowrap;">
                        {{ number_format(($avance / $total) * 100, 0) }}% Completado
                    </small>
                </div>
                @endif
            </div>
            </div>
    
            <div class="card-body center-content">
                <form method="POST" action="{{ route('encuesta.preguntas.confirmar')}}"> 
                    @csrf 
                      
                    <h6>{{$seccion->enunciado}}</h6>
                    <div class="survey-table">
                                <!-- Table Header -->
                                <div class="table-header">
                                    <div class="question-col">Pregunta</div>
                                    @foreach ($opciones as $opcion)
                                        <div class="options-col">{{$opcion->nombre}}</div>
                                    @endforeach    
                                </div>

                                <!-- Questions and Options -->
                                @foreach ($preguntas as $pregunta)
                                    <div class="table-row">
                                        <div class="question-col">
                                            <span class="question-number">{{ $pregunta->orden }}.</span>
                                            {{$pregunta->pregunta }}<span style="color:red">*</span>
                                        </div>
                                        @foreach ($pregunta->opciones as $opcion)
                                            <div class="options-col" data-option-name="{{$opcion->nombre}}">
                                                <input type="radio" 
                                                    id="{{$prefijoPreguntas.$pregunta->orden.$sufijoPreguntas}}_{{$opcion->pivot->valor_id}}" 
                                                    name="{{$prefijoPreguntas.$pregunta->orden.$sufijoPreguntas}}" 
                                                    value="{{ $opcion->valor_encontrado }}"
                                                    class="custom-radio"
                                                    {{ old($prefijoPreguntas.$pregunta->orden.$sufijoPreguntas) ==  $opcion->valor_encontrado ? 'checked' : '' }}>
                                            </div>
                                        @endforeach
                                        @error($prefijoPreguntas . $pregunta->orden .$sufijoPreguntas) 
                                            <div class="error-row">
                                                <span class="error-message">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                         <!-- Hidden inputs -->
                                @endforeach
                                <input type="hidden" name="confirma" value="">
                                    <input type="hidden" name="tipo" value="{{strtoupper($seccion->tipo)}}">
                                    <input type="hidden" name="registro" value="{{$fichaDato->registro}}">
                                    <input type="hidden" name="empresa" value="{{$fichaDato->empresas}}">
                                    <input type="hidden" name="area" value="{{$fichaDato->nombredepto}}">
                                    <input type="hidden" name="sede" value="{{$fichaDato->sede}}">
                                    <input type="hidden" name="nombre" value="{{$fichaDato->nombre}}">
                                    <input type="hidden" name="cargo" value="{{$fichaDato->cargoempresa}}">
                                    <input type="hidden" name="NumeroFolio" value="{{$fichaDato->NumeroFolio}}">
                                    <input type="hidden" name="cedula" value="{{$fichaDato->cedula}}">
                                    <input type="hidden" name="periodo" value="{{$fichaDato->periodo}}">
                                    <input type="hidden" name="proximaSeccionId" value="{{ $proximaSeccionId }}">
                                    <input type="hidden" name="rutaActual" value="{{ $seccion->route }}">
                                
                                    <button type="submit" class="submit-btn">Continuar</button>
                            </div>                       
                        </div>
                    </form>
                </div>    
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
        <script src="{{ asset('js/ocultar-errores.js') }}"></script>
@endpush  



<script>
    document.addEventListener("DOMContentLoaded", function () {
        let progressBar = document.getElementById("progressBar");
        let total = {{ $total }};
        let avance = {{ $avance }};

        function actualizarProgreso(nuevoAvance) {
            let porcentaje = (nuevoAvance / total) * 100;
            progressBar.style.width = porcentaje + "%";
        }
    });
</script>

<style>
    .progress-container {
        position: fixed;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 10px;
        background-color: #d6dde5;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar {
        width: 0;
        height: 100%;
        background-color: #0D47A1;
        transition: width 0.5s ease-in-out;
    }
</style>







