@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view section-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header center-paragraph">Encuesta de factores de Riesgo Psicosocial</div>
            <div class="card-body">
                <form action="{{route('encuesta.advertencia')}}" nethod = "GET">
                <div class="row">
                    <div class="d-flex justify-content-center">
                        <p class="center-paragraph">!USUARIO HABILITADO!<br>
                        <strong>{{Auth::user()->nombre}}</strong>
                        Esta accediendo como empleado de la empresa <strong> {{Auth::user()->lugartrabajo}}.</strong>
                        Su cédula <strong>{{Auth::user()->cedula}} </strong> se encuentra habilitada para acceder a la encuesta de FACTORES DE RIESGO PSICOSOCIAL</p>  
                    </div>
                    <div class="d-flex justify-content-center">
                        <input class="submit-btn" type="submit" value="Iniciar">
                    </div>  
                  </form>    
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection