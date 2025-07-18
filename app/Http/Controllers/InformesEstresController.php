<?php

namespace App\Http\Controllers;

use App\Models\InformeEstres;
use App\Http\Util\UtilitariosInforme as Util;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InformesEstresController extends Controller

{
    public static function generarInformeEstres($user, $fichadatos){
        $tabla = $user->nivelSeguridad == config('constants.TIPO_B') ? 'estres1a' : 'estres1';
        $criterios_suma1 = $user->nivelSeguridad == config('constants.TIPO_B') ? 'estres1a + estres2a + estres3a + estres4a + estres5a + estres6a + estres7a + estres8a' : 'estres1 + estres2 + estres3 + estres4 + estres5 + estres6 + estres7 + estres8';
        $criterios_suma2 = $user->nivelSeguridad == config('constants.TIPO_B') ? 'estres9a + estres10a + estres11a + estres12a' : 'estres9 + estres10 + estres11 + estres12';
        $criterios_suma3 = $user->nivelSeguridad == config('constants.TIPO_B') ? 'estres13a + estres14a + estres15a + estres16a + estres17a + estres18a + estres19a + estres20a + estres21a + estres22a' : 'estres13 + estres14 + estres15 + estres16 + estres17 + estres18 + estres19 + estres20 + estres21 + estres22';
        $criterios_suma4 = $user->nivelSeguridad == config('constants.TIPO_B') ? 'estres23a + estres24a + estres25a + estres26a + estres27a + estres28a + estres29a + estres30a + estres31a' : 'estres23 + estres24 + estres25 + estres26 + estres27 + estres28 + estres29 + estres30 + estres31';
                                                                    
        $sumas = DB::table($tabla)  
            ->where($tabla.'.registro', $fichadatos->registro)
            ->select(
                DB::raw('('.$criterios_suma1.') AS suma1'),
                DB::raw('('.$criterios_suma2.') AS suma2'),
                DB::raw('('.$criterios_suma3.') AS suma3'),
                DB::raw('('.$criterios_suma4.') AS suma4')
            )
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    'suma1' => $item->suma1,
                    'suma2' => $item->suma2,
                    'suma3' => $item->suma3,
                    'suma4' => $item->suma4,
                ];
            })
            ->toArray();
        
        $rango = $user->nivelSeguridad == config('constants.TIPO_B') ? [6.5,11.8,17,23.4,100] : [7.8,12.6,17.7,25,100];   
       
        $divisorTransformado = 61.16;
        
        $sintomasFisiologicos = Util::calcularRiesgoYPuntajeEstres($sumas,'suma1',null,8,4,$divisorTransformado,$rango);
        
        $sintomasComportamiento = Util::calcularRiesgoYPuntajeEstres($sumas,'suma2',null,4,3,$divisorTransformado,$rango);

        $sintomasIntelectuales = Util::calcularRiesgoYPuntajeEstres($sumas,'suma3',null,10,2,$divisorTransformado,$rango);        

        $sintomasPsicoemocionales = Util::calcularRiesgoYPuntajeEstres($sumas,'suma4',null,9,1,$divisorTransformado,$rango);

        $sumasRiesgos = ($sintomasFisiologicos->puntaje ?? 0) + 
        ($sintomasComportamiento->puntaje ?? 0) + 
        ($sintomasIntelectuales->puntaje ?? 0) + 
        ($sintomasPsicoemocionales->puntaje ?? 0);
        
        $totalSintomasEstres = Util::calcularRiesgoYPuntajeEstres(null,null,$sumasRiesgos,4,1,$divisorTransformado,$rango);
        
        $data = [
            'NumeroFolio'=>$fichadatos->NumeroFolio,
            'registro'=>$fichadatos->registro,
            'estres_cedula'=>$fichadatos->cedula, 
            'estres_lugartrabajo'=>$fichadatos->empresas, 
            'estres_depto'=>$fichadatos->cargoempresa,
            'estres_ciudad'=>$fichadatos->lugartrabajocity, 
            'estres_transformado1'=>$sintomasFisiologicos->puntaje, 
            'estres_transformado2'=>$sintomasComportamiento->puntaje, 
            'estres_transformado3'=>$sintomasIntelectuales->puntaje, 
            'estres_transformado4'=>$sintomasPsicoemocionales->puntaje, 
            'estres_transformado5'=>$totalSintomasEstres->puntaje, 
            'estres_rta1'=>$sintomasFisiologicos->riesgo, 
            'estres_rta2'=>$sintomasComportamiento->riesgo, 
            'estres_rta3'=>$sintomasIntelectuales->riesgo, 
            'estres_rta4'=>$sintomasPsicoemocionales->riesgo, 
            'estres_rta5'=>$totalSintomasEstres->riesgo,
            'periodo'=>$fichadatos->periodo
        ];
        
        try {
            $modelInfo = InformeEstres::where('registro',$fichadatos->registro)->first();
            !empty($modelInfo) ?  InformeEstres::where('registro',$data['registro'])->update($data) : InformeEstres::create($data);
        } catch (Exception $exception) {
            Log::error('Error registrando informe extralaboral: ', $exception);
            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }
        
    }   
}