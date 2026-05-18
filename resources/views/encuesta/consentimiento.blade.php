@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/consentimiento.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row justify-content-center" style="margin:0; padding-top:20px;">
    <div class="col-md-8">
        <div class="card" style="margin-bottom:120px;">
            <div class="card-header center-paragraph-bold">Consentimiento</div>
            <div class="card-body">

                <div class="row mb-2">
                    <p class="center-paragraph">
                        <strong>¡BIENVENIDO!</strong><br>
                        Usted está ingresando a la encuesta: Factores de Riesgo Psicosocial, recuerde que su plazo para finalizar el proceso es hasta: <strong>{{Auth::user()->fecha_final}}</strong>
                    </p>
                </div>

                <div class="consentimiento-scroll">
                    <p class="text-center">
                        <strong>DECLARACIÓN EXPRESA DE CONSENTIMIENTO INFORMADO Y MANEJO DE DATOS</strong><br>
                        <em>(Programa prevención de factores de riesgo psicosocial)</em>
                    </p>

                    <p>Yo <strong>{{ Auth::user()->nombre }}</strong> identificado con C.C. N° <strong>{{ Auth::user()->cedula }}</strong> de <strong>{{ Auth::user()->delaciudad }}</strong>, en mi condición de trabajador de la empresa <strong>{{ Auth::user()->lugartrabajo }}</strong>, manifiesto que me han explicado y he comprendido satisfactoriamente la naturaleza y propósito del Programa de Prevención en Riesgo Psicolaboral.</p>

                    <p>En consecuencia, doy mi consentimiento para que me practiquen las pruebas psicotécnicas, instrumentos de medición de los factores de riesgo psicosocial (Intralaborales, extralaborales e individuales) encuestas de información sociodemográfica, entrevistas y procedimientos que se encuentran enmarcados en el protocolo del Programa de Prevención en riesgos psicosociales que contribuyan a generar diagnósticos confiables y que hagan parte del ambiente laboral.</p>

                    <p>Soy consciente que este proceso es voluntario y no atenta contra mi derecho fundamental a la intimidad personal y laboral, por el contrario, busca promover un programa para prevenir situaciones psíquico-orgánicas que puedan afectar mi salud física, emocional y mental, o de igual forma impactar en mi desempeño laboral.</p>

                    <p>Se me informa que el resultado del diagnóstico generará un plan de recomendaciones e intervenciones en éste tipo de riesgo, a las que manifiesto mi compromiso de asistir de manera activa, acorde a mi responsabilidad frente al cuidado y preservación de mi salud, de acuerdo con lo estipulado en el Decreto 1295 de 1994 y ley 1562 de 2012. Los datos serán utilizados para los fines pertinentes de seguridad y salud en el trabajo, de acuerdo con lo dispuesto en la Resolución 2646 de 2008; Decreto 2346 de 2007 y Ley 1090 de 2006. La empresa tendrá conocimiento de los resultados de riesgo psicosocial, lo que ayudará al desarrollo de planes de mitigación de los riesgos en beneficio de los trabajadores, ya que es importante crear ambientes de trabajo saludables para reducir factores como el estrés o enfermedades laborales.</p>

                    <p>En cumplimiento del Régimen General de Habeas Data, regulado por la Ley 1581 de 2012 y sus Decretos reglamentarios, con el ingreso de mis datos personales en las encuestas que forman parte de la Batería de Instrumentos para la Medición de factores de Riesgo Psicosocial, autorizo de manera voluntaria, previa, expresa e informada a <strong>GENNCO LTDA.</strong> identificada con Nit 900.068.175-8 en calidad de RESPONSABLE, para tratar mis datos personales de acuerdo con su Política de Tratamiento de Datos Personales (<a href="https://www.gennco.com.co/politicas.html" target="_blank">https://www.gennco.com.co/politicas.html</a>) GENNCO LTDA. queda autorizado para recolectar, compilar, almacenar, usar, circular, compartir, comunicar, procesar, actualizar, cruzar, transferir, transmitir, depurar, suprimir y disponer mis datos personales aquí suministrados, de acuerdo con las finalidades relacionadas con el objeto social de la Compañía y en especial para la elaboración del Diagnóstico de Riesgo Psicosocial de la empresa. En caso de querer ejercer sus derechos de acceso, consulta, rectificación, actualización y supresión de sus datos personales puede enviar un correo a <a href="mailto:protecciondatospersonales@gennco.com.co">protecciondatospersonales@gennco.com.co</a> indicando la acción a realizar.</p>

                    <p>Por lo anterior, confirmo que deseo realizar la encuesta de Riesgo Psicosocial de manera voluntaria.</p>
                </div>

                <div class="consentimiento-firma">

                    {{-- SI / NO buttons --}}
                    <div id="botonesConsentimiento" class="row mt-3 mb-2">
                        <div class="col-6">
                            <form action="{{route('encuesta.consentimiento.aceptar')}}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn red btn-circle" name="consentimiento" type="submit" value="{{config('constants.USUARIO_NIEGA')}}">
                                    <i class="fas fa-times"></i>
                                </button>
                                <span style="vertical-align:middle; margin-left:10px;">No Deseo realizar la encuesta</span>
                            </form>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn green btn-circle" onclick="mostrarFirma()">
                                <i class="fas fa-check"></i>
                            </button>
                            <span style="vertical-align:middle; margin-left:10px;">Deseo realizar la encuesta</span>
                        </div>
                    </div>

                    {{-- FIRMA section — revealed when user clicks "Deseo realizar" --}}
                    <div id="seccionFirma" style="display:none; margin-top:12px;">
                        <div class="row align-items-end mb-2">
                            <div class="col-12 col-md">
                                <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
                                    <strong>FIRMA:</strong>
                                    <button type="button" onclick="clearFirma()"
                                        style="font-size:0.72em; color:#888; background:none; border:none; padding:0; cursor:pointer;">
                                        &#x21BA; Borrar firma
                                    </button>
                                </div>
                                <canvas id="firmaCanvas" width="400" height="45"
                                    style="width:100%; border-bottom:1px solid #495057; cursor:crosshair; display:block; touch-action:none; background:#fff; pointer-events:auto; position:relative; z-index:9999;">
                                </canvas>
                            </div>
                            <div class="col-12 col-md-auto mt-2 mt-md-0" style="padding-bottom:20px;">
                                <form id="formAceptar" action="{{route('encuesta.consentimiento.aceptar')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="consentimiento" value="{{config('constants.USUARIO_CONFIRMA')}}">
                                    <input type="hidden" name="firma" id="firmaData">
                                    <input type="hidden" name="fecha_firma" id="fechaFirmaData">
                                    <button type="submit" class="btn btn-circle green"
                                        style="width:100%; height:auto; padding:8px 20px; border-radius:20px; font-size:0.88em;">
                                        Acepto consentimiento.
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/consentimiento.js') }}"></script>
@endpush
