@extends('layouts.app')
@section('content')
<div class="row justify-content-center centered-view">
    <div class="col-md-10">
        <div class="card" style="height: auto">
            <div class="card-header text-center">
                Terminos y condiciones de la aplicacion web
            </div>
            <div class="card-body">
               
                <!-- Added a fixed height container with scroll -->
                <div class="terms-content center-content">
                    <div class="text-justify">
                        <form action = "{{route('encuesta.advertencia')}}">
                        <p>GENNCO LTDA ha desarrollado el aplicativo FACTORES DE RIESGO PSICOSOCIAL como una herramienta para responder de manera virtual la encuesta diseñada por el MINISTERIO DE PROTECCION SOCIAL con el apoyo de la PONTIFICIA UNIVERSIDAD JAVERIANA.</p>

                        <p>Ésta encuesta se realiza de acuerdo a los lineamientos de la Resolución 2646 de 2008 y la Batería de Herramientas para la Medición de Factores de Riesgo Psicosocial, con el objetivo de minimizar los riesgos ocupacionales y direccionar los Programas de Vigilancia Epidemiológica y Seguridad & Salud en el trabajo de la Compañía.</p>

                        <p>Gennco Ltda. está avalada con licencia en Seguridad y Salud en el Trabajo, la cual mantendrá en custodia y absoluta reserva los datos registrados, garantizando así la confidencialidad de la información. Es importante recordar que se entregará a la empresa un informe con el análisis de datos estadísticos de forma grupal de las percepciones de todos los empleados. Por esta razón, le invitamos a contestar la prueba con la mayor confianza de la custodia de sus respuestas.</p>

                        <p>Cualquier persona que haga uso de la aplicación FACTORES DE RIESGO PSICOSOCIAL, que se ofrece dentro de la página www.gennco.com.co, será denominada en lo sucesivo Usuario, para efectos de estos Términos y Condiciones de Uso.</p>

                        <p>Los usuarios deben ser mayores de 18 años y su registro solo es posible por medio del envío de la BASE DE DATOS suministrada por la EMPRESA CLIENTE, la cual contrata el servicio con GENNCO LTDA.</p>

                        <p>GENNCO LTDA., asigna un usuario y clave para el ingreso al aplicativo FACTORES DE RIESGO PSICOSOCIAL, por lo tanto, la responsabilidad de la información registrada u omitida para realizar la encuesta, corresponde única y exclusivamente al usuario.</p>

                        <p>GENNCO LTDA. se reserva el derecho a excluir de los servicios registrados, a todo Usuario que haya entregado datos INCOMPLETOS o FALSOS, conforme a los lineamientos técnicos de la herramienta.</p>

                        <p>El usuario de la página, debe respetar los emblemas, marcas comerciales registradas y demás objetos de propiedad intelectual de GENNCO LTDA. Los derechos de autor sobre las páginas, diseños de imágenes y en general todo el contenido que aparece en pantalla, así como sobre la información y el material contenidos en las mismas, que son propiedad intelectual de GENNCO LTDA.</p>

                        <p>El usuario manifiesta que se le han explicado y ha comprendido satisfactoriamente la naturaleza y propósito del Programa de Prevención en Riesgo Psicolaboral.</p>

                        <p>En consecuencia, da su consentimiento para que le practiquen las pruebas psicotécnicas, instrumentos de medición de los factores de riesgo psicosocial, encuestas de información sociodemográfica, entrevistas y procedimientos que se encuentran enmarcados en el protocolo del Programa de Prevención en riesgos psicosociales que contribuyan a generar diagnósticos confiables.</p>

                        <p>El usuario es consciente que este proceso es voluntario y no atenta contra su derecho fundamental a la intimidad personal y laboral, por el contrario, busca promover un programa para prevenir situaciones psíquico-orgánicas que puedan afectar su salud física, emocional y mental, o de igual forma impactar en su desempeño laboral.</p>

                        <p>Se le informa que el resultado del diagnóstico generará un plan de recomendaciones e intervenciones en éste tipo de riesgo, a las que manifiesta su compromiso de asistir de manera activa, acorde a su responsabilidad frente al cuidado y preservación de su salud, de acuerdo con lo estipulado en el Decreto 1295 de 1994 y ley 1562 de 2012. Los datos serán utilizados para los fines pertinentes de seguridad y salud en el trabajo, de acuerdo con lo dispuesto en la Resolución 2646 de 2008; Decreto 2346 de 2007 y Ley 1090 de 2006.</p>

                        <p>En cumplimiento del Régimen General de Habeas Data, regulado por la Ley 1581 de 2012 y sus Decretos reglamentarios, con el ingreso de mis datos personales en las encuestas que forman parte de la Batería de Instrumentos para la Medición de factores de Riesgo Psicosocial, autorizo de manera voluntaria, previa, expresa e informada a GENNCO LTDA. identificada con Nit 900.068.175-8 en calidad de RESPONSABLE, para tratar mis datos personales de acuerdo con su Política de Tratamiento de Datos Personales, publicada en www.gennco.com.co.</p>
                        <div class="d-flex justify-content-center">
                            <input class="submit-btn" type="submit" value="Volver">
                        </div> 
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
@endSection