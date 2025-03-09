@extends('layouts.app')
@section('content')
    <div class="row justify-content-center centered-view">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header center-paragraph-bold" class="center-paragraph">Ficha de datos</div>
                        <div class="card-body center-content" >
                            <h6>Las siguientes son algunas preguntas que se refieren a información general de usted o su
                                ocupación</h6>
                                <form action="{{route('encuesta.fichadatos.confirmar')}}" method="POST">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="empresas">Empresa o entidad</label>
                                            <i class="fas fa-building"></i>
                                            <input class="read-only" id="empresas" name="empresas" type="text" placeholder="Nombre de la compañia" value="{{Auth::user()->lugartrabajo}}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="sede">Sede/ sucursal/ sección</label>
                                            <i class="fas fa-city"></i>
                                            <input class="read-only" id="sede" name="sede" type="text" placeholder="Sede o sucursal" value="{{Auth::user()->sede}}" readonly>
                                        </div>
                                        <div class="form-group full-width">
                                            <label for="nombre">Nombre Completo</label>
                                            <i class="fas fa-user"></i>
                                            <input class="read-only" id="nombre" name="nombre" type="text" placeholder="Nombre completo" value="{{Auth::user()->nombre}}" readonly>
                                        </div>
                                        <div class="form-group ">
                                            <label for="cedula">Identificación</label>
                                            <i class="fas fa-id-card"></i>
                                            <input class="read-only" id="cedula" name="cedula" type="text" placeholder="Numero de identificación" value="{{Auth::user()->cedula}}" readonly>
                                             <input type = "hidden" id="registro" name="registro" value="{{Auth::user()->registro}}">
                                             <input type = "hidden" id="periodo" name="periodo" value="{{Auth::user()->periodo}}">
                                            <input type = "hidden" id="tablacontestada" name="tablacontestada" value="condiciones-ambientales">
                                        </div>
                                        <div class="form-group checkbox-group">
                                            <div class="row" col=12>
                                                <div class="row" col=12>
                                                    <label for="sexo">Sexo<span style="color:red">*</span></label>
                                                </div>
                                                <div class="row" col=6>
                                                    <label>
                                                        <input type="radio" id="sexo" name="sexo" value="MASCULINO" {{ old('sexo') == 'MASCULINO' ? 'checked' : '' }}> Masculino
                                                    </label>
                                                </div>
                                                <div class="row" col=6>
                                                    <label>
                                                        <input type="radio" id="sexo" name="sexo" value="FEMENINO" {{ old('sexo') == 'FEMENINO' ? 'checked' : '' }}> Femenino
                                                    </label>
                                                </div> 
                                                @error('sexo') 
                                                    <span class="error-message">{{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="anonaci">Año de nacimiento <span style="color:red">*</span></label>
                                            <i class="fas fa-birthday-cake"></i>
                                            <select id="anonaci" name="anonaci" class="editable">
                                                <option value="" disabled selected>Seleccione un año</option>
                                                @foreach ($anios as $anio)
                                                    <option value="{{ $anio }}" {{ old('anonaci') == $anio ? 'selected' : '' }}>{{ $anio }}</option>
                                                @endforeach
                                            </select>
                                            @error('anonaci')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="estadociv">Estado Civil<span style="color:red">*</span></label>
                                            <i class="fas fa-heart"></i>
                                            <select id="estadociv" name="estadociv" class="editable">
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value="CASADO" {{ old('estadociv') == 'CASADO'? 'selected' : '' }}>Casado(a)</option>
                                                <option value="SOLTERO" {{ old('estadociv') == 'SOLTERO' ? 'selected' : '' }}>Soltero(a)</option>
                                                <option value="UNION LIBRE" {{ old('estadociv') == 'UNION LIBRE' ? 'selected' : '' }}>Union libre</option>
                                                <option value="SEPARADO" {{ old('estadociv') == 'SEPARADO' ? 'selected' : '' }}>Separado(a)</option>
                                                <option value="DIVORCIADO" {{ old('estadociv') == 'DIVORCIADO' ? 'selected' : '' }}>Divorciado(a)</option>
                                                <option value="VIUDO" {{ old('estadociv') == 'VIUDO' ? 'selected' : '' }}>Viudo(a)</option>
                                                <option value="RELIGIOSO" {{ old('estadociv') == 'RELIGIOSO' ? 'selected' : '' }}>Religioso(a)</option>
                                            </select>
                                            @error('estadociv')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nivelEstudio">Nivel de estudios<span style="color:red">*</span></label>
                                            <i class="fas fa-user-graduate"></i>
                                            <select id="nivelEstudio" name="nivelEstudio" class="editable">
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value="NINGUNO" {{ old('nivelEstudio') == 'NINGUNO'? 'selected' : '' }}>Ninguno</option>
                                                <option value="PRIMARIA INCOMPLETA" {{ old('nivelEstudio') == 'PRIMARIA INCOMPLETA'? 'selected' : '' }}>Primaria incompleta</option>
                                                <option value="PRIMARIA COMPLETA" {{ old('nivelEstudio') == 'PRIMARIA COMPLETA'? 'selected' : '' }}>Primaria completa</option>
                                                <option value="BACHILLERATO INCOMPLETO" {{ old('nivelEstudio') == 'BACHILLERATO INCOMPLETO"'? 'selected' : '' }}>Bachillerato in completo</option>
                                                <option value="BACHILLERATO COMPLETO" {{ old('nivelEstudio') == 'BACHILLERATO COMPLETO'? 'selected' : '' }}>Bachillerato completo</option>
                                                <option value="TECNICO INCOMPLETO" {{ old('nivelEstudio') == 'TECNICO INCOMPLETO'? 'selected' : '' }}>Técnico / tecnológico incompleto</option>
                                                <option value="TECNICO COMPLETO" {{ old('nivelEstudio') == 'TECNICO COMPLETO'? 'selected' : '' }}>Técnico / tecnológico completo</option>
                                                <option value="PROFESIONAL INCOMPLETO" {{ old('nivelEstudio') == 'PROFESIONAL INCOMPLETO'? 'selected' : '' }}>Profesional incompleto</option>
                                                <option value="PROFESIONAL COMPLETO" {{ old('nivelEstudio') == 'PROFESIONAL COMPLETO'? 'selected' : '' }}>Profesional incompleto</option>
                                                <option value="CARRERA MILITAR" {{ old('nivelEstudio') == 'CARRERA MILITAR'? 'selected' : '' }}>Carrera Militar / Policía</option>
                                                <option value="POST-GRADO INCOMPLETO" {{ old('nivelEstudio') == 'POST-GRADO INCOMPLETO'? 'selected' : '' }}>Post-grado incompleto</option>
                                                <option value="POST-GRADO COMPLETO" {{ old('nivelEstudio') == 'POST-GRADO COMPLETO'? 'selected' : '' }}>Post-grado completo</option>
                                            </select>
                                            @error('nivelEstudio')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="ocupacion">Ocupación<span style="color:red">*</span></label>
                                            <i class="fas fa-hard-hat"></i>
                                            <input class="editable" id="ocupacion" name="ocupacion" type="text" placeholder="Ocupación u oficio" value="{{old('ocupacion')}}">
                                            @error('ocupacion')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div> 
                                        
                                        <div class="form-group">
                                            <label for="residenciadepto">Departamento de residencia<span style="color:red">*</span></label>
                                            <i class="fas fa-map-marker-alt"></i>
                                            <select id="residenciadepto" name="residenciadepto" class="editable">
                                                <option value="" disabled selected>Selecccione una opción</option>
                                                @foreach ($departamentos as $departamento)
                                                    <option value="{{ $departamento->departamento }}" {{ old('residenciadepto') == $departamento->id_departamento ? 'selected' : '' }}>{{ $departamento->departamento }}</option>
                                                @endforeach
                                            </select>
                                            @error('residenciadepto')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="residenciaciudad">Ciudad de residencia<span style="color:red">*</span></label>
                                            <i class="fas fa-map-marker-alt"></i>
                                            <select id="residenciaciudad" name="residenciaciudad" class="editable" disabled>
                                                 <option value="" disabled selected>Selecccione una opción</option>
                                            </select>
                                            @error('residenciaciudad')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="estrato">Estrato de servicios públicos<span style="color:red">*</span></label>
                                            <i class="fas fa-poll"></i>
                                            <select id="estrato" name="estrato" class="editable">
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value=1 {{ old('estrato') == 1 ? 'selected' : '' }}>1</option>
                                                <option value=2 {{ old('estrato') == 2 ? 'selected' : '' }}>2</option>
                                                <option value=3 {{ old('estrato') == 3 ? 'selected' : '' }}>3</option>
                                                <option value=4 {{ old('estrato') == 4 ? 'selected' : '' }}>4</option>
                                                <option value=5 {{ old('estrato') == 5 ? 'selected' : '' }}>5</option>
                                                <option value=6 {{ old('estrato') == 6 ? 'selected' : '' }}>6</option>
                                                <option value="FINCA" {{ old('estrato') == 'FINCA' ? 'selected' : '' }}>Finca</option>
                                                <option value="NO SE" {{ old('estrato') == 'NO SE' ? 'selected' : '' }}>No sé</option>
                                            </select>
                                            @error('estrato')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tipoVivienda">Tipo de vivienda<span style="color:red">*</span></label>
                                            <i class="fas fa-home"></i>
                                            <select id="tipoVivienda" name="tipoVivienda" class="editable">
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value="PROPIA" {{ old('tipoVivienda') == 'PROPIA' ? 'selected' : '' }}>Propia</option>
                                                <option value="EN ARRIENDO" {{ old('tipoVivienda') == 'EN ARRIENDO' ? 'selected' : '' }}>En Arriendo</option>
                                                <option value="FAMILIAR" {{ old('tipoVivienda') == 'FAMILIAR' ? 'selected' : '' }}>Familiar</option>
                                            </select>
                                            @error('tipoVivienda')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="personasdependientes">Número de personas que dependen economicamente<span style="color:red">*</span></label>
                                            <i class="fas fa-users"></i>
                                            <input class="editable" id="personasdependientes" name="personasdependientes" type="number" placeholder="Ingrese el numero de personas" value="{{old('personasdependientes')}}">
                                            @error('personasdependientes')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="lugartrabajodpto">Departamento donde trabaja</label>
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input  class="read-only" id="lugartrabajodpto" name="lugartrabajodpto" type="text" placeholder="Departamento donde trabaja" value="{{Auth::user()->delaciudad}}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="lugartrabajocity">Ciudad donde trabaja actualmente</label>
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input  class="read-only" id="lugartrabajocity" name="lugartrabajocity" type="text" placeholder="Ciudad donde trabaja" value="{{Auth::user()->deptociudad}}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="tiempotrabajo">Hace cuantos años trabaja en esta empresa<span style="color:red">*</span></label>
                                            <i class="fas fa-calendar-check"></i>
                                            <select id="tiempotrabajo" name="tiempotrabajo" class="editable">
                                                <option value="" disabled selected>Seleccione un opción</option>
                                                <option value="MENOS DE 1 AÑO" {{ old('tiempotrabajo') == 'MENOS DE 1 AÑO' ? 'selected' : '' }}>Menos de un año</option>
                                                <option value="MAS DE 1 AÑO" {{ old('tiempotrabajo') == 'MAS DE 1 AÑO' ? 'selected' : '' }}>Mas de un año</option>
                                            </select>
                                            @error('tiempotrabajo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="cargoempresa">Cargo que ocupa en la empresa<span style="color:red">*</span></label>
                                            <i class="fas fa-hard-hat"></i>
                                            <input  class="read-only" id="cargoempresa" name="cargoempresa" type="text" placeholder="Nombre completo" value="{{Auth::user()->cargo}}" readonly>
                                        </div>         
                                        <div class="form-group">
                                            <label for="tipocargo">Tipo del cargo<span style="color:red">*</span></label>
                                            <i class="fas fa-briefcase"></i>                                           
                                            <select id="tipocargo" name="tipocargo" class="editable">
                                                <option value="" disabled selected>Seleccione una opción lo mas parecida<span style="color:red">*</span></option>
                                                <option value="Jefatura" {{ old('tipocargo') == 'Jefatura' ? 'selected' : '' }}>Jefatura: tiene personal a cargo</option>
                                                <option value="Profesional, Analista, Técnico, Tecnólogo" {{ old('tipocargo') == 'Profesional, Analista, Técnico, Tecnólogo' ? 'selected' : '' }}>Profesional, Analista, Técnico, Tecnólogo</option>
                                                <option value="Auxiliar, Asistente Administrativo, Asistente técnico" {{ old('tipocargo') == 'Auxiliar, Asistente Administrativo, Asistente técnico' ? 'selected' : '' }}>Auxiliar, Asistente Administrativo, Asistente técnico</option>
                                                <option value="Operario, Operador, Ayudante, Servicios" {{ old('tipocargo') == 'Operario, Operador, Ayudante, Servicios' ? 'selected' : '' }}>Operario, Operador, Ayudante, Servicios</option>
                                            </select>
                                            @error('tipocargo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror 
                                        </div>
                                        <div class="form-group">
                                            <label for="anoscargo">Hace cuantos años trabaja en el cargo<span style="color:red">*</span></label>
                                            <i class="fas fa-calendar-check"></i>
                                            <select id="anoscargo" name="anoscargo" class="editable">
                                                <option value="" disabled selected>Seleccione un opción</option>
                                                <option value="MENOS DE 1 AÑO" {{ old('anoscargo') == 'MENOS DE 1 AÑO' ? 'selected' : '' }}>Menos de un año</option>
                                                <option value="MAS DE 1 AÑO" {{ old('anoscargo') == 'MAS DE 1 AÑO' ? 'selected' : '' }}>Mas de un año</option>
                                            </select>
                                            @error('anoscargo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror 
                                        </div>

                                        <div class="form-group">
                                            <label for="nombredepto">Area de trabajo<span style="color:red">*</span></label>
                                            <i class="fas fa-network-wired"></i>
                                            <input  class="read-only" id="nombredepto" name="nombredepto" type="text" placeholder="Area de trabajo" value="{{Auth::user()->areatrabajo}}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="contratoactual">Tipo de contrato actual<span style="color:red">*</span></label>
                                            <i class="fas fa-file-signature"></i>
                                            <select id="contratoactual" name="contratoactual" class="editable">
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value="Temporal menos de un año" {{ old('contratoactual') == 'Temporal menos de un año' ? 'selected' : '' }}>Temporal menos de un año</option>
                                                <option value="Temporal mas de un año" {{ old('contratoactual') == 'Temporal mas de un año' ? 'selected' : '' }}>Temporal mas de un año</option>
                                                <option value="Termino indefinido" {{ old('contratoactual') == 'Termino indefinido' ? 'selected' : '' }}>Termino indefinido</option>
                                                <option value="Cooperado (Cooperativa)" {{ old('contratoactual') == 'Cooperado (Cooperativa)' ? 'selected' : '' }}>Cooperado (Cooperativa)</option>
                                                <option value="Prestación de servicios" {{ old('contratoactual') == 'Prestación de servicios' ? 'selected' : '' }}>Prestación de servicios</option>
                                                <option value="No se">No sé</option>
                                            </select>
                                            @error('contratoactual')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror 
                                        </div>

                                        <div class="form-group">
                                            <label for="horastrabajo">Número de horas que trabaja al día<span style="color:red">*</span></label>
                                            <i class="fas fa-business-time"></i>
                                            <input class="editable"  id="horastrabajo" name="horastrabajo" type="number" placeholder="Numero de horas trabajadas" value="{{old('horastrabajo')}}">
                                            @error('horastrabajo')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror 
                                        </div>
	           
                                        <div class="form-group">
                                            <label for="tiposalario">Tipo de salario<span style="color:red">*</span></label>
                                            <i class="fas fa-money-check-alt"></i>
                                            <select id="tiposalario" name="tiposalario" class="editable">
                                            <option value selected disabled = "">Seleccione Opción...</option>
                                            <option value="fijo" {{ old('tiposalario') == 'fijo' ? 'selected' : '' }}>Fijo(Diario, Semanal, Quincenal, Mensual)</option>
                                            <option value="fijo y variable"  {{ old('tiposalario') == 'fijo y variable' ? 'selected' : '' }}>Una parte fija, Otra variable</option>
                                            <option value="Variable"  {{ old('tiposalario') == 'Variable' ? 'selected' : '' }}>Todo variable(A destajo, Por producción, Por comisión)</option>
                                            </select>
                                            @error('tiposalario')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror 
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="submit-btn">Continuar</button>
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