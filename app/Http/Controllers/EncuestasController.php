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
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $user_registro = Auth::user()->registro;
        try{
            $empleado =  Empleado::where('registro',$user_registro);
            if($request->consentimiento == config('constants.USUARIO_CONFIRMA')){
                $empleado->update([
                            'consentimiento' => $request->input('consentimiento')
                        ]);
                         
                return redirect()->route('encuesta.fichadatos');            
            }else{
                $empleado->update([
                    'consentimiento' => $request->input('consentimiento'),
                    'habilitado' => config('constants.USUARIO_COMPLETO'),
                    'llave' => config('constants.USUARIO_LLAVE')
                ]); 
                return redirect()->intended('encuesta/no-consentimiento');    
            } 
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
       
        try {
            
            $modelInfo = Fichadato::where('registro',$request->registro)->first();
            !empty($modelInfo) ?  FichaDato::where('registro',$data['registro'])->update($data) : FichaDato::create($data);

            return redirect()->route('encuesta.preguntas', [strtolower(Auth::user()->nivelSeguridad), $data['tablacontestada']])->with('success', '¡Sus datos fueron registrados!');
        } catch (Exception $exception) {
            Log::error('Error registrando ficha datos: ', $exception);

            return redirect()->route('encuesta.fichadatos')->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
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
       
        $seccion = Seccion::where('tipo', $request->tipo)->where('route', $request->rutaActual)->first();

        if (!$seccion) {
            return redirect()->back()
                ->with('error', 'Sección no encontrada.');
        }

        
        $fichaDato = Fichadato::where('registro', Auth::user()->registro)->first();
    
        $proximaSeccionId = $this->obtenerFinPreguntas($request->input('proximaSeccionId'));

        $incluyePreguntas = $this->esSeccionConPreguntas($request->rutaActual);
       
        try{
            if($incluyePreguntas){
            
                $data = $this->obenterRequestData($request, $request->rutaActual);
                
  
                $modelClass = $seccion->modelo;
            
               
                if (!class_exists($modelClass) ) {
                    return redirect()->back()
                                    ->with('error', 'Modelo no válido.');
                }
    
                $modelInfo = $modelClass::where('registro',$data['registro'])->first();

                !empty($modelInfo) ?  $modelClass::where('registro',$data['registro'])->update($data) : $modelClass::create($data);

                $fichaDato->update(['tablacontestada' => $proximaSeccionId]);

                if($proximaSeccionId == config('constants.SECCION_FIN_ENCUESTA')){
                    $empleado = Empleado::where('registro',Auth::user()->registro);
                    $empleado->update(['habilitado'=>config('constants.USUARIO_COMPLETO'),
                        'llave'=>config('constants.USUARIO_LLAVE'),
                        'tipoencuesta'=>config('constants.USUARIO_CONSTESTO')
                    ]);

                }
            }
             
            if($request->input('confirma') != null){
            
                if($request->rutaActual == config('constants.SECCION_CONFIRMA_ATENCION')){
                    $proximaSeccionId = $request->input('confirma') == config('constants.USUARIO_APLICA') ? $request->input('proximaSeccionId') : config('constants.SECCION_CONFIRMA_JEFE');
                    if($proximaSeccionId == config('constants.SECCION_CONFIRMA_JEFE')){
                        $proximaSeccionId = $this->aplicaEncuestaJefes($proximaSeccionId,$request->tipo);
                    }
                    $fichaDato->update([
                        'serviciocliente' => $request->input('confirma'),
                        'tablacontestada' => $proximaSeccionId
                    ]);

                }

                if($request->rutaActual == config('constants.SECCION_CONFIRMA_JEFE')){
                    $proximaSeccionId = $request->input('confirma') == config('constants.USUARIO_APLICA') ? $request->input('proximaSeccionId') : config('constants.SECCION_CONDICIONES_VIVIENDA');
                    $fichaDato->update([
                        'soyjefe' => $request->input('confirma'),
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

    public function obtenerValorPreguntas($seccion){
    
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

    public function esSeccionConPreguntas ($ruta){
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

    public function obtenerPrefijoPreguntas ($ruta){
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


    public function obtenerSufijoPreguntas ($tipo , $ruta){
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

    public function obtenerQueryPreguntas($tipo, $seccion_id, $tipoModelo, $ruta){
        
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
                InformesExtralaboralesController::generarInformeExtralaboral($fichaDato); 
                break; 
            
            case config('constants.SECCION_ESTRES'):
                InformesEstresController::generarInformeEstres($fichaDato); 
                break;
                
            case config('constants.SECCION_AFRONTAMIENTO_III'):
                InformesAfrontamientoController::generarInformeAfrontamiento($fichaDato); 
                break;      
             
            default:
                return false;
            
        }
    }

}
    
