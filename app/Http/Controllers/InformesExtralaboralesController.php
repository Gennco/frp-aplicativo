<?php

namespace App\Http\Controllers;

use App\Models\ExtralaboralA;
use App\Models\ExtralaboralB;
use App\Models\InformeExtralaboral;
use App\Http\Util\UtilitariosInforme as Util;
use Illuminate\Support\Facades\DB;

class InformesExtralaboralesController extends Controller
{
    public static function generarInformeExtralaboral($user, $fichadatos){

        $sumaTiempoFueraTrabajo = ExtralaboralB::where('registro', $fichadatos->registro)
        ->selectRaw('ext14 + ext15 + ext16 + ext17 as suma')
        ->pluck('suma')
        ->first();

        $sumaRelacionesFamiliares = ExtralaboralB::where('registro', $fichadatos->registro)
        ->selectRaw('ext22 + ext25 + ext27 as suma')
        ->pluck('suma')
        ->first();

        $sumaRelacionesComunicaciones = ExtralaboralB::where('registro', $fichadatos->registro)
        ->selectRaw('ext18 + ext19 + ext20 + ext21 + ext23 as suma')
        ->pluck('suma')
        ->first();

        $sumaSituacionEconomica = ExtralaboralB::where('registro', $fichadatos->registro)
        ->selectRaw('ext29 + ext30 + ext31 as suma')
        ->pluck('suma')
        ->first();

        $sumaCondicionesVivienda = ExtralaboralA::where('registro', $fichadatos->registro)
        ->selectRaw('ext5 + ext6 + ext7 + ext8 + ext9 + ext10 + ext11 + ext12 + ext13 as suma')
        ->pluck('suma')
        ->first();

        $sumaInfluenciaExtralaboral = ExtralaboralB::where('registro', $fichadatos->registro)
        ->selectRaw('ext24 + ext26 + ext28 as suma')
        ->pluck('suma')
        ->first();

        $sumaDesplazamientoVivienda = ExtralaboralA::where('registro', $fichadatos->registro)
        ->selectRaw('ext1 + ext2 + ext3 + ext4 as suma')
        ->pluck('suma')
        ->first();

        $tiempoFueraTrabajo = Util::calcularRiesgoYPuntajeDominios($sumaTiempoFueraTrabajo,16,[6.3,25,37.5,50,100]);

        $rangoRF = $user->nivelSeguridad == config('constants.TIPO_A') ? [0.9,8.3,16.7,25,100] : [0.9,8.3,25,33.3,100];
        $relacionesFamiliares =  Util::calcularRiesgoYPuntajeDominios($sumaRelacionesFamiliares,12,$rangoRF);
        
        $rangoRC = $user->nivelSeguridad == config('constants.TIPO_A') ? [0.9,10,20,30,100] : [5,15,25,35,100];
        $relacionesComunicacion = Util::calcularRiesgoYPuntajeDominios($sumaRelacionesComunicaciones,20,$rangoRC);

        $rangoSE = $user->nivelSeguridad == config('constants.TIPO_A') ? [8.3,25,33.3,50,100] : [16.7,25,41.7,50,100];
        $situacionEconomica =  Util::calcularRiesgoYPuntajeDominios($sumaSituacionEconomica,12,$rangoSE);

        $rangoCV = $user->nivelSeguridad == config('constants.TIPO_A') ? [5.6,11.1,13.9,22.2,100] : [5.6,11.1,16.7,27.8,100];
        $condicionesVivienda =  Util::calcularRiesgoYPuntajeDominios($sumaCondicionesVivienda,36,$rangoCV);

        $rangoIE = $user->nivelSeguridad == config('constants.TIPO_A') ? [8.3,16.7,25,41.7,100] : [0.9,16.7,25,41.7,100];
        $influenciaEntornoExtralaboral =  Util::calcularRiesgoYPuntajeDominios($sumaInfluenciaExtralaboral,12,$rangoIE);

        $desplazamientoVivienda = Util::calcularRiesgoYPuntajeDominios($sumaDesplazamientoVivienda,16,[0.9,12.5,25,43.8,100]);

        $sumaTotalExtralaboral = ($sumaTiempoFueraTrabajo ?? 0 ) + ($sumaRelacionesFamiliares ?? 0) + ($sumaRelacionesComunicaciones ?? 0 ) + ($sumaSituacionEconomica ?? 0)
        + ($sumaCondicionesVivienda ?? 0 ) + ($sumaInfluenciaExtralaboral ?? 0) + ($sumaDesplazamientoVivienda ?? 0);
        $rangoTotal = $user->nivelSeguridad == config('constants.TIPO_A') ? [11.3,16.9,22.6,29,100] : [12.9,17.7,24.2,32.3,100];
        $totalExtraLaboral = Util::calcularRiesgoYPuntajeDominios($sumaTotalExtralaboral,124, $rangoTotal);
        
        $data=[
            'NumeroFolio'=>$fichadatos->NumeroFolio,
            'registro'=>$fichadatos->registro, 
            'extra_cedula'=>$fichadatos->cedula, 
            'extra_lugartrabajo'=>$fichadatos->empresas,
            'extra_depto'=>$fichadatos->cargoempresa,
            'extra_ciudad'=>$fichadatos->lugartrabajocity, 
            'extra_transformado1'=>$tiempoFueraTrabajo->puntaje, 
            'extra_respuesta1'=>$tiempoFueraTrabajo->riesgo, 
            'extra_transformado2'=>$relacionesFamiliares->puntaje, 
            'extra_respuesta2'=>$relacionesFamiliares->riesgo, 
            'extra_transformado3'=>$relacionesComunicacion->puntaje,
            'extra_respuesta3'=>$relacionesComunicacion->riesgo, 
            'extra_transformado4'=>$situacionEconomica->puntaje,
            'extra_respuesta4'=>$situacionEconomica->riesgo, 
            'extra_transformado5'=>$condicionesVivienda->puntaje, 
            'extra_respuesta5'=>$condicionesVivienda->riesgo, 
            'extra_transformado6'=>$influenciaEntornoExtralaboral->puntaje, 
            'extra_respuesta6'=>$influenciaEntornoExtralaboral->riesgo, 
            'extra_transformado7'=>$desplazamientoVivienda->puntaje, 
            'extra_respuesta7'=>$desplazamientoVivienda->riesgo, 
            'extra_transformadototal'=>$totalExtraLaboral->puntaje, 
            'extra_respuestatotal'=>$totalExtraLaboral->riesgo, 
            'periodo'=>$fichadatos->periodo
        ];

        try {
            $modelInfo = InformeExtralaboral::where('registro',$fichadatos->registro)->first();
            !empty($modelInfo) ?  InformeExtralaboral::where('registro',$data['registro'])->update($data) : InformeExtralaboral::create($data);
        } catch (Exception $exception) {
            Log::error('Error registrando informe extralaboral: ', $exception);
            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }
    }
}