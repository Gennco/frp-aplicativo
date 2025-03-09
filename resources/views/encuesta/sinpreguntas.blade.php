@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view section-center">
    <div class="col-md-8">
        <div class="card"> 
            <div class="card-header center-paragraph">Encuesta tipo {{strtoupper($seccion->tipo)}} - {{$seccion->nombre}}</div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                            @if($seccion->route == 'fin-encuesta')
                                <form action="{{ route('empleado.logout') }}" method="POST">
                                @csrf
                            @else        
                                <form method="POST" action="{{ route('encuesta.preguntas.confirmar')}}"> 
                                @csrf    
                            @endif         
                            @if($seccion->route == 'fin-encuesta')
                                <P class="center-paragraph"><strong>¡GRACIAS POR SU PARTICIPACION!</strong></P>
                                <p class="center-paragraph">{{$seccion->enunciado}}</p>
                                <input class="submit-btn" type="submit" value="Salir">
                            @elseif($seccion->route == 'confirma-atencion' || $seccion->route == 'confirma-jefe' )
                                            
                            <h5>{{$seccion->enunciado}}</h5>
                            <div class="row d-flex justify-content-center">
                                <input type="hidden" name="tipo" value="{{strtoupper($seccion->tipo)}}">
                                <input type="hidden" name="rutaActual" value="{{ $seccion->route }}">
                                <input type="hidden" name="proximaSeccionId" value="{{ $proximaSeccionId }}">
                                <div class="col-6" >
                                        <button class="btn green btn-circle" name="confirma" type="submit" value="{{config('constants.USUARIO_APLICA')}}">
                                            <i class="fas fa-check"></i><!-- Icon for "Check" -->
                                        </button>
                                        <span style="vertical-align: button; margin-left: 10px;">SI</span>
                                </div>
                                <div class="col-6">
                                        <button class="btn red btn-circle" name="confirma" type="submit" value="{{config('constants.USUARIO_NO_APLICA')}}">
                                            <i class="fas fa-times"></i>  <!-- Icon for "X" -->
                                        </button>
                                        <span style="vertical-align: button; margin-left: 10px;">NO</span> 
                                </div> 
                            </div>    
                            @endif        
                    </div>    
                </div>
            </div>     
        </div>
    </div>           
</div>
@endsection   
   
   
   
  