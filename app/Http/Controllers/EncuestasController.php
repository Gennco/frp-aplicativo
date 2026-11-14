<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Fichadato;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Seccion;
use App\Models\Opcion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use App\Http\Requests\FichadatosValidacion;
use App\Http\Requests\PreguntasRequest;
use App\Http\Controllers\InformesIntralaboralesController;
use App\Http\Controllers\InformesExtralaboralesController;
use App\Http\Controllers\InformesEstresController;
use App\Http\Controllers\InformesAfrontamientoController;
use Illuminate\Support\Facades\Config;


class EncuestasController extends Controller
{
    public function index(){
        $user = Auth::user();
        
            if($user->terminos == config('constants.USUARIO_ESPERA')){
               
                return view('encuesta.inicio');
            }    
            
            if($user->consentimiento == config('constants.USUARIO_ESPERA')){
                return redirect()->route('encuesta.consentimiento');
            }

            if (
                $user->consentimiento == config('constants.USUARIO_NIEGA') ||
                $user->tipoencuesta == 'noconsentimiento'
            ) {
                return redirect()->route('encuesta.no-consentimiento');
            }
                
            if ($user->consentimiento == config('constants.USUARIO_CONFIRMA') && $user->fichadatos == config('constants.USUARIO_ESPERA')){
                return redirect()->route('encuesta.fichadatos');
            } 
            
            if ($user->consentimiento == config('constants.USUARIO_CONFIRMA') && $user->fichadatos == config('constants.USUARIO_CONFIRMA')){
                return redirect()->route('encuesta.preguntas', [strtolower($user->nivelSeguridad), $user->fichadato->tablacontestada]);
            } 
                
    }

    public function mostrarAdvertencia(){
        return view('encuesta.advertencia');
    }

    public function mostrarTerminos(){
        return view('encuesta.terminos');
    }

    public function mostrarConsentimiento(){
        $user = Auth::user();
        if (
            $user->terminos !== config('constants.USUARIO_CONFIRMA') ||
            $user->consentimiento !== config('constants.USUARIO_ESPERA')
        ) {
            return redirect()->route('encuesta.inicio');
        }

        return view('encuesta.consentimiento');
    } 

    public function mostrarNoConsentimiento(){
        return view('encuesta.finencuesta');
    }

    public function noPermitido(){
        return view('encuesta.noPermitido');
    }

    public function aceptarTerminos(Request $request){
        $user_registro = Auth::user()->registro;

        $rules = [
            'terminos' => ['required'],
        ];

        $messages =[
            'terminos.required' => 'Debe aceptar terminos y condiciones.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try{
            Empleado::where('registro',$user_registro)
            ->update(['terminos'=>$request->input('terminos')]); 
            
        }catch(ModelNotFoundException $exception){
            Log::error('Empleado no encontrado: ', $exception);
            return back()->withError(config('MENSAJE_ERROR_MODELO_NOT_FOUND'))->withInput(); 
        }
       
        return redirect()->route('encuesta.consentimiento');
    }

    public function aceptarConsentimiento(Request $request){
        $request->validate([
            'consentimiento' => ['required', Rule::in([
                config('constants.USUARIO_CONFIRMA'),
                config('constants.USUARIO_NIEGA'),
            ])],
        ]);

        $user = Auth::user();
        if (
            $user->terminos !== config('constants.USUARIO_CONFIRMA') ||
            $user->consentimiento !== config('constants.USUARIO_ESPERA')
        ) {
            return redirect()->route('encuesta.inicio');
        }

        $updates = $request->input('consentimiento') === config('constants.USUARIO_CONFIRMA')
            ? [
                'consentimiento' => config('constants.USUARIO_CONFIRMA'),
                'firma' => $request->input('firma'),
                'fecha_firma' => $request->input('fecha_firma'),
                'ciudad_expedicion' => $request->input('ciudad_expedicion'),
            ]
            : [
                'consentimiento' => config('constants.USUARIO_NIEGA'),
                'fichadatos' => config('constants.USUARIO_NIEGA'),
                'habilitado' => config('constants.USUARIO_COMPLETO'),
                'tipoencuesta' => 'noconsentimiento',
                'llave' => config('constants.USUARIO_LLAVE'),
            ];

        try{
            $updated = Empleado::where('registro', $user->registro)
                ->where('terminos', config('constants.USUARIO_CONFIRMA'))
                ->where('consentimiento', config('constants.USUARIO_ESPERA'))
                ->update($updates);

            if (!$updated) {
                return redirect()->route('encuesta.inicio');
            } 

            return $request->input('consentimiento') === config('constants.USUARIO_CONFIRMA')
                ? redirect()->route('encuesta.fichadatos')
                : redirect()->route('encuesta.no-consentimiento');
        }catch(ModelNotFoundException $exception){
            Log::error('Empleado no encontrado: ', $exception);
            return back()->withError(config('MENSAJE_ERROR_MODELO_NOT_FOUND'))->withInput();
        }
    } 

    public function mostrarFichadatos()
    {
        $departamentos = Departamento::all();

        $hoy = Carbon::now()->format('Y');
        $inicio_anio = $hoy-100;
        $fin_anio = $hoy-16;

        $anios = [];
        for($i = $inicio_anio;  $i<= $fin_anio; $i++){
            $anios[] = $i;
        }
        return view('encuesta.fichadatos', compact('departamentos', 'anios'));
    }


    public function confirmaFichadatos(FichadatosValidacion $request){
         $validatedData = $request->validated();
       
         $additionalData = $request->only(['empresas', 'sede', 'nombre','cedula','lugartrabajodpto','lugartrabajocity', 'nombredepto', 'registro','periodo','cargoempresa','tablacontestada']);
         $edad = Carbon::now()->format('Y') - $validatedData['anonaci'];
         $additionalData['edad']   = $edad;
       
        $data = array_merge($validatedData, $additionalData);
        $data['registro'] = Auth::user()->registro;
        $secciones = $this->obtenerRutasValidas(
            strtoupper(Auth::user()->nivelSeguridad),
            Auth::user()->afrontamiento,
            Auth::user()->adicional
        );
        $primeraSeccion = $secciones->first();
        if (!$primeraSeccion) {
            return redirect()->back()
                ->with('error', 'No hay secciones configuradas para esta encuesta.')
                ->withInput();
        }
        $data['tablacontestada'] = $primeraSeccion->route;
        $data['ocupacion'] = mb_convert_encoding($data['ocupacion'], 'UTF-8', 'UTF-8');
       
        $maxAttempts = 5;
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            try {
                DB::transaction(function () use ($data) {
                    FichaDato::updateOrCreate(
                        ['registro' => $data['registro']],
                        $data
                    );

                    Empleado::where('registro', $data['registro'])
                        ->update(['fichadatos' => config('constants.USUARIO_CONFIRMA')]);
                }, 5);
                
                return redirect()->route('encuesta.preguntas', [
                    strtolower(Auth::user()->nivelSeguridad), 
                    $data['tablacontestada']
                ])->with('success', '¡Sus datos fueron registrados!');
            
            } catch (\Illuminate\Database\QueryException $exception) {
                if ($exception->getCode() == 1213) { // Deadlock error
                    $attempts++;
                    Log::warning("Deadlock detected on attempt #$attempts. Retrying...");
                    sleep(1);
                } else {
                    Log::error('Error registrando ficha datos: ' . $exception->getMessage());
                    return redirect()->route('encuesta.fichadatos')->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
                }
            } catch (Exception $exception) {
                Log::error('Error registrando ficha datos: ' . $exception->getMessage());
                return redirect()->route('encuesta.fichadatos')->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
            }
        }    
    }

    public function mostrarPreguntas(Request $request){    
        $fichaDato = Fichadato::where('registro', Auth::user()->registro)->first();
        if((strtoupper($request->tipo)) != Auth::user()->nivelSeguridad){
            $ruta = $fichaDato->tablacontestada;
            $tipo = Auth::user()->nivelSeguridad;
            return redirect()->route('encuesta.preguntas.noPermitido')->with(['tipo' => strtolower($tipo), 'ruta' => $ruta]);
            }
        $secciones = $this->obtenerRutasValidas(strtoupper($request->tipo), Auth::user()->afrontamiento, Auth::user()->adicional);
        $total = $secciones->count();
        $indiceSeccion = $this->obtenerIndiceAvance($secciones, $request->seccion); 
        $seccion = $secciones->get($indiceSeccion);
        $avance = $indiceSeccion + 1;
        $preguntas = [];
        $prefijoPreguntas= null;
        $proximaSeccionId = $seccion->route != config('constants.SECCION_FIN_ENCUESTA') ? $secciones->get(($avance + 1) - 1)->route : config('constants.SECCION_FIN_ENCUESTA');  
        $incluyePreguntas = $this->esSeccionConPreguntas($seccion->route);
        
        if($incluyePreguntas){
            $prefijoPreguntas = $this->obtenerPrefijoPreguntas($seccion->route);
            $sufijoPreguntas = $this->obtenerSufijoPreguntas($seccion->tipo, $seccion->route);
            $opciones = $this->obtenerOpcionesPreguntas($seccion->route);
            
            $preguntas = $this->obtenerValorPreguntas($seccion); 
            return view('encuesta.preguntas', compact('preguntas','seccion','fichaDato', 'proximaSeccionId', 'prefijoPreguntas', 'sufijoPreguntas', 'opciones','total','avance'));
        }
        return view('encuesta.sinpreguntas', compact('seccion','fichaDato', 'proximaSeccionId'));
    }

    public function confirmarPreguntas(PreguntasRequest $request){
        $tipo = strtoupper(Auth::user()->nivelSeguridad);
        $rutaActual = $request->input('rutaActual');
        $seccion = Seccion::where('tipo', $tipo)->where('route', $rutaActual)->first();

        if (!$seccion) {
            return redirect()->back()
                ->with('error', 'Sección no encontrada.');
        }

        $fichaDato = Fichadato::where('registro', Auth::user()->registro)->first();
        if (!$fichaDato) {
            return redirect()->back()
                ->with('error', 'No se encontró el avance actual de la encuesta.');
        }

        $secciones = $this->obtenerRutasValidas(
            $tipo,
            Auth::user()->afrontamiento,
            Auth::user()->adicional
        );
        $rutaGuardada = $fichaDato->tablacontestada;
        $indiceRutaGuardada = $this->obtenerIndiceAvance($secciones, $rutaGuardada);
        if ($indiceRutaGuardada === false) {
            return redirect()->back()
                ->with('error', 'No se pudo determinar el avance actual de la encuesta.');
        }

        if (
            strtoupper($request->input('tipo')) !== $tipo ||
            $rutaGuardada !== $rutaActual
        ) {
            return redirect()->route('encuesta.preguntas', [
                'tipo' => strtolower($tipo),
                'seccion' => $rutaGuardada,
            ]);
        }

        $indiceFinEncuesta = $this->obtenerIndiceAvance(
            $secciones,
            config('constants.SECCION_FIN_ENCUESTA')
        );
        if ($indiceFinEncuesta !== $secciones->count() - 1) {
            return redirect()->back()
                ->with('error', 'La sección final no está correctamente configurada.');
        }

        $indiceSeccion = $this->obtenerIndiceAvance($secciones, $rutaActual);
        if ($indiceSeccion === false) {
            return redirect()->back()
                ->with('error', 'La sección no está disponible para esta encuesta.');
        }

        $siguienteSeccion = $secciones->get($indiceSeccion + 1);
        if (!$siguienteSeccion) {
            return redirect()->back()
                ->with('error', 'No hay una siguiente sección configurada para esta encuesta.');
        }
        $proximaSeccionId = $this->obtenerFinPreguntas($siguienteSeccion->route);

        $incluyePreguntas = $this->esSeccionConPreguntas($rutaActual);

        try{
            if($incluyePreguntas){

                $data = $this->obenterRequestData($request, $rutaActual);
                $data['registro'] = Auth::user()->registro;
                
  
                $modelClass = $seccion->modelo;
            
               
                if (!class_exists($modelClass) ) {
                    return redirect()->back()
                                    ->with('error', 'Modelo no válido.');
                }
    
                $modelInfo = $modelClass::where('registro',$data['registro'])->first();

                !empty($modelInfo) ?  $modelClass::where('registro',$data['registro'])->update($data) : $modelClass::create($data);

                $fichaDato->update(['tablacontestada' => $proximaSeccionId]);

                if($proximaSeccionId === config('constants.SECCION_FIN_ENCUESTA')){
                    $empleado = Empleado::where('registro',Auth::user()->registro);
                    $empleado->update(['habilitado'=>config('constants.USUARIO_COMPLETO'),
                        'llave'=>config('constants.USUARIO_LLAVE'),
                        'tipoencuesta'=>config('constants.USUARIO_CONSTESTO')
                    ]);

                }
            }
             
            if (!$incluyePreguntas) {
                $confirma = $request->input('confirma');
                if (!in_array($confirma, [
                    config('constants.USUARIO_APLICA'),
                    config('constants.USUARIO_NO_APLICA'),
                ], true)) {
                    return redirect()->back()
                        ->with('error', 'La respuesta de confirmación no es válida.');
                }

                if($rutaActual == config('constants.SECCION_CONFIRMA_ATENCION')){
                    $proximaSeccionId = $confirma == config('constants.USUARIO_APLICA') ? $proximaSeccionId : config('constants.SECCION_CONFIRMA_JEFE');
                    if($proximaSeccionId == config('constants.SECCION_CONFIRMA_JEFE')){
                        $proximaSeccionId = $this->aplicaEncuestaJefes($proximaSeccionId,$tipo);
                    }
                    $fichaDato->update([
                        'serviciocliente' => $confirma,
                        'tablacontestada' => $proximaSeccionId
                    ]);

                }

                if($rutaActual == config('constants.SECCION_CONFIRMA_JEFE')){
                    $proximaSeccionId = $confirma == config('constants.USUARIO_APLICA') ? $proximaSeccionId : config('constants.SECCION_CONDICIONES_VIVIENDA');
                    $fichaDato->update([
                        'soyjefe' => $confirma,
                        'tablacontestada' => $proximaSeccionId
                    ]);

                }
            }

            $this->generarInforme($seccion->route, $proximaSeccionId, $request->tipo, $fichaDato);

            return redirect()->route('encuesta.preguntas', ['tipo' =>strtolower($request->tipo),'seccion'=>$proximaSeccionId])
                            ->with('success', 'Respuestas guardadas correctamente.');
        } catch (Exception $exception) {
            Log::error('Error registrando encuesta: ', $exception);
                
            return redirect()->route('encuesta.preguntas', ['tipo' =>strtolower($request->tipo),'seccion'=>$seccion->route])->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }
    }

    public function obtenerMunicipios($departamento){
        $departamento = Departamento::where('departamento', $departamento)->first();
        $municipios = Municipio::where('id_departamento', $departamento->id_departamento)->get();
        return response()->json($municipios);
    }

    public static function obtenerValorPreguntas($seccion){
    
        $preguntas = self::obtenerQueryPreguntas($seccion->tipo, $seccion->id, $seccion->modeloPregunta,$seccion->route);
        $preguntas->each(function ($pregunta) {
            $pregunta->opciones->each(function ($opcion) {
                $valor = $opcion->valor->firstWhere('id', $opcion->pivot->valor_id); 
                if ($valor) {
                    $opcion->valor_encontrado = $valor->valor;
                }
            });
        });
        return $preguntas;
    }

    public static function esSeccionConPreguntas ($ruta){
        switch($ruta){
            case config('constants.SECCION_CONFIRMA_JEFE'):
                return false;
             
            case config('constants.SECCION_CONFIRMA_ATENCION'):
                return false;
              
            case config('constants.SECCION_FIN_ENCUESTA'):
                return false; 
            
            default : 
                return true;
            
        }        
    }

    public static function obtenerPrefijoPreguntas ($ruta){
        switch($ruta){
            case config('constants.SECCION_CONDICIONES_VIVIENDA'):
                return 'ext';

            case config('constants.SECCION_CONDICIONES_EXTRA'):
                return 'ext';    
            
            case config('constants.SECCION_ESTRES'):
                return 'estres';        
            
            default : 
                return "p";
        }        
    }


    public static function obtenerSufijoPreguntas ($tipo , $ruta){
        if($tipo == config('constants.TIPO_B') && $ruta == config('constants.SECCION_ESTRES')){
            return "a";
        }
        return null;        
    }

    public function obtenerOpcionesPreguntas ($ruta){
       switch($ruta){
            case config('constants.SECCION_ESTRES'):
                return  Opcion::whereIn('id', [1, 2, 6, 5])
                ->orderBy('orden', 'asc')
                ->get();
            case config('constants.SECCION_AFRONTAMIENTO_I'):
                return  Opcion::whereIn('id', [6, 7, 5, 4, 2, 1])
                ->orderBy('orden', 'desc')
                ->get();

            case config('constants.SECCION_AFRONTAMIENTO_II'):
                return  Opcion::whereIn('id', [6, 7, 5, 4, 2, 1])
                ->orderBy('orden', 'desc')
                ->get(); 
                
            case config('constants.SECCION_AFRONTAMIENTO_III'):
                return  Opcion::whereIn('id', [6, 7, 5, 4, 2, 1])
                ->orderBy('orden', 'desc')
                ->get(); 
            case config('constants.SECCION_PERSONALIDAD'):
                return  Opcion::whereIn('id', [8,9])
                ->orderBy('orden', 'asc')
                ->get();   
           
            default:
                return Opcion::whereIn('id', [1, 2, 3, 4,5])
            ->orderBy('orden', 'asc')
            ->get(); 

       }   
    }

    public function obtenerFinPreguntas($ruta){

        $tieneAfrontamiento = $this->validarEncuestaAdicional(Auth::user()->afrontamiento);
        $tienePersonalidad = $this->validarEncuestaAdicional(Auth::user()->adicional);

        if($ruta == config('constants.SECCION_AFRONTAMIENTO_I')){
            
            if($tieneAfrontamiento && $tienePersonalidad){
                return config('constants.SECCION_AFRONTAMIENTO_I');
            }

            else if (!$tieneAfrontamiento && $tienePersonalidad){
                return config('constants.SECCION_PERSONALIDAD');
            }

            else if (!$tieneAfrontamiento && !$tienePersonalidad){
                return config('constants.SECCION_FIN_ENCUESTA');
            }
        }
        
        if($ruta == config('constants.SECCION_PERSONALIDAD')){
            if($tienePersonalidad){
                return config('constants.SECCION_PERSONALIDAD');
            }else{
                return config('constants.SECCION_FIN_ENCUESTA');
            }
        }

        return $ruta;
    }
    
    public function validarEncuestaAdicional ($ruta){
        switch($ruta){
            case 'SI':
                return true;

            case 'NO':
                return false;    
            
            default : 
                return false;
        }        
    }

    public function obenterRequestData(Request $request, $ruta){
        
        switch($ruta){
            case config('constants.SECCION_AFRONTAMIENTO_I'):
                $coincidencia ='afrontamiento';
                break;
            case config('constants.SECCION_AFRONTAMIENTO_II'):
                $coincidencia ='afrontamiento';  
                break;
            case config('constants.SECCION_AFRONTAMIENTO_III'):
                $coincidencia ='afrontamiento';
                break;
            case config('constants.SECCION_PERSONALIDAD'):
                $coincidencia ='personalidad';    
                break;
            default : 
                $coincidencia =null;
        } 

        if ($coincidencia == 'afrontamiento') {
            return $request->except(['_token', 'tipo', 'proximaSeccionId','rutaActual','confirma']);
        }    
        else if ($coincidencia == 'personalidad'){
            return $request->except(['_token', 'tipo', 'proximaSeccionId','rutaActual','confirma']);
        }else {  
            return $request->except(['_token', 'tipo', 'proximaSeccionId','rutaActual','confirma','sede','area','cargo','nombre']);
        }

    }

    public function aplicaEncuestaJefes($proximaSeccionId, $tipo){
        if($tipo == config('constants.TIPO_B')){
            return config('constants.SECCION_CONDICIONES_VIVIENDA');
        }else{
            return $proximaSeccionId;
        }
    }

    public static function obtenerQueryPreguntas($tipo, $seccion_id, $tipoModelo, $ruta){
        
        if($tipo == config('constants.TIPO_B')){
            switch ($ruta) {
                case config('constants.SECCION_CONDICIONES_VIVIENDA') :
                    return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_EXT_CONDICIONES_VIVIENDA'))->with(['opciones.valor'])->orderBy('orden','asc')->get();

                case config('constants.SECCION_CONDICIONES_EXTRA') :
                     return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_EXT_CONDICIONES_EXTRALABORAL'))->with(['opciones.valor'])->orderBy('orden','asc')->get();

                case config('constants.SECCION_ESTRES') :
                    return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_ESTRES'))->with(['opciones.valor'])->orderBy('orden','asc')->get();

                case config('constants.SECCION_AFRONTAMIENTO_I') :
                    return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_AFRONTAMIENTO_I'))->with(['opciones.valor'])->orderBy('orden','asc')->get();
                
                case config('constants.SECCION_AFRONTAMIENTO_II') :
                    return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_AFRONTAMIENTO_II'))->with(['opciones.valor'])->orderBy('orden','asc')->get(); 
                
                case config('constants.SECCION_AFRONTAMIENTO_III') :
                    return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_AFRONTAMIENTO_III'))->with(['opciones.valor'])->orderBy('orden','asc')->get();     
            
                case config('constants.SECCION_PERSONALIDAD') :
                    return $tipoModelo::where('seccion_id', config('constants.ID_SECCION_PERSONALIDAD'))->with(['opciones.valor'])->orderBy('orden','asc')->get();    
                
                default: 
                  return $tipoModelo::where('seccion_id', $seccion_id)->with(['opciones.valor'])->orderBy('orden','asc')->get();
            }   
        }
        
        return $tipoModelo::where('seccion_id', $seccion_id)->with(['opciones.valor'])->orderBy('orden','asc')->get();
    }

    public function obtenerIndiceAvance($secciones, $ruta){
       
        $indice = $secciones->search(function ($seccion) use ($ruta) {
            return $seccion['route'] == $ruta;
        });

        return $indice;
    }

    public function obtenerRutasValidas($tipo, $afrontamiento, $personalidad)
    {
        $secciones = Seccion::where('tipo', $tipo)->orderBy('orden','asc')->get();
        $rutasAEliminar = collect();

        if ($afrontamiento == config('constants.USUARIO_NIEGA')) {
            $rutasAEliminar->push(config('constants.SECCION_AFRONTAMIENTO_I'));
            $rutasAEliminar->push(config('constants.SECCION_AFRONTAMIENTO_II'));
            $rutasAEliminar->push(config('constants.SECCION_AFRONTAMIENTO_III'));
        }

        if ($personalidad == config('constants.USUARIO_NIEGA')) {
            $rutasAEliminar->push(config('constants.SECCION_PERSONALIDAD'));
        }

        $secciones = $secciones->reject(function ($seccion) use ($rutasAEliminar) {
            return $rutasAEliminar->contains($seccion['route']);
        })->values();

        return $secciones;
    }

    public function generarInforme($ruta, $proximaSeccionId, $tipo, $fichaDato){
        switch ($ruta) {
            case config('constants.SECCION_CONFIRMA_ATENCION'):
                if(($tipo == config('constants.TIPO_B') ) && $proximaSeccionId == config('constants.SECCION_CONDICIONES_VIVIENDA')){
                    InformesIntralaboralesController::generarInformeIntraB($fichaDato);
                }
                break;

            case  config('constants.SECCION_ATENCION-CLIENTE'): 
                if($tipo == config('constants.TIPO_B')){
                    InformesIntralaboralesController::generarInformeIntraB($fichaDato);
                }
                break;
            case config('constants.SECCION_CONFIRMA_JEFE'):
                if($proximaSeccionId == config('constants.SECCION_CONDICIONES_VIVIENDA')){
                    InformesIntralaboralesController::generarInformeIntraA($fichaDato);
                }
                break;
            case config('constants.SECCION_SOY_JEFE'):
                InformesIntralaboralesController::generarInformeIntraA($fichaDato); 
                break;

            case config('constants.SECCION_CONDICIONES_EXTRA'):
                InformesExtralaboralesController::generarInformeExtralaboral(Auth::user(),$fichaDato); 
                break; 
            
            case config('constants.SECCION_ESTRES'):
                InformesEstresController::generarInformeEstres(Auth::user(),$fichaDato); 
                break;
                
            case config('constants.SECCION_AFRONTAMIENTO_III'):
                InformesAfrontamientoController::generarInformeAfrontamiento($fichaDato); 
                break;      
             
            default:
                return false;
            
        }
    }

}
    
