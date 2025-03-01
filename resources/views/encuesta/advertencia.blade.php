@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view">
<div class="col-md-8">
  <div class="card"> 
      <div class="card-header center-paragraph-bold">Advertencia</div>
      <div class="card-body">
        <form  method="POST" action="{{route('encuesta.terminos.aceptar')}}" name="form-advise" id="login-advice" >
        @csrf
          <p class="justify-paragraph"> Este cuestionario de factores psicosociales busca conocer su opinión sobre algunos aspectos de su trabajo. <br>
            Le agradecemos que usted se sirva  contestar a las preguntas de forma absolutamente sincera. Las respuestas que usted de al cuestionarío, no son ni buenas, ni malas, lo importante es que reflejen su manera de pensar sobre su trabajo. <br>
            Al responder por favor lea cuidadosamente cada pregunta, luego piense como es su trabajo y responda a todas las preguntas, en cada una de ellas marque una sola respuesta. <br>
            Tenga presente que el cuestionario <b>NO lo evalúa a usted como trabajador</b>, sino busca conocer cómo es el trabajo que le han asignado. <br>
            Sus respuestas seran manejadas de forma <b>absolutamente confidencial</b>. </p>
          
          <p class="center-paragraph">
            <strong> Su registro será el número {{Auth::user()->registro}}. </strong>
          </p>
          <p class="center-paragraph">            
            <input title="Debe aceptar términos y condiciones" type="radio" name="terminos" value="{{config('constants.USUARIO_CONFIRMA')}}" id="terminos">
            <a href="{{route('encuesta.terminos')}}">
              <span>
              Acepto los Terminos y Condiciones
            </span>
            </a>
            <input class="submit-btn" type="submit" value="Ir a la encuesta">
             (Consentimiento informado).
          </p>
          <p class="justify-paragraph">
            Contacte con nosotros al correo <span style="color:#33F"> webmaster@gennco.com.co</span>, si experimenta problemas o no tiene datos de acceso.
          </p>
        </form>
      </div>
  </div>
</div>
</div>
@endsection  