@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view section-center">
    <div class="col-md-8">
            <div class="card"> 
                <div class="card-header center-paragraph">Fin de la encuesta</div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                            <p class="center-paragraph"> Usted ha indicado que <strong>NO </strong>desea realizar la encuesta de Factores de Riesgo Psicosocial.<br>
                            Por favor cierre la sesión</p>
                    </div> 
                    <div class="d-flex justify-content-center">
                        <form action="{{ route('empleado.logout') }}" method="POST">
                            @csrf  
                            <input class="submit-btn" type="submit" value="Salir">
                        </form>
                    </div>
                </div>     
            </div>
        </div>        
</div>
@endsection