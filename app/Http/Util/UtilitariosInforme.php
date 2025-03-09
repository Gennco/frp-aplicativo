<?php

namespace App\Http\Util;

use Illuminate\Support\Facades\Log;

class UtilitariosInforme
{
    public function calcularRiesgoYPuntajeDimensiones($sumas, $claveSuma, $divisorPromedio, $rangosRiesgo) {
        $valorSuma = $sumas[$claveSuma] ?? null;
        Log::Info('valor suma dimension',['suma'=>$valorSuma]);
        $promedio = $valorSuma ? $valorSuma / $divisorPromedio : null;
        Log::Info('promedio', ['promedio'=>$promedio]);
        $total = $promedio ? $promedio * 100 : null;
        Log::Info('total', ['total'=>$total]);
        $transformado = self::transformarNumero($total);
        Log::Info('transformado', ['transformado'=>$transformado]);
        $riesgo = self::determinarRiesgo($transformado, $rangosRiesgo);
        Log::Info('riesgo', ['riesgo'=>$riesgo]);
        $puntaje = $transformado ?? 0;
        Log::Info('puntaje', ['puntaje'=>$puntaje]);
    
        return (object) [
            'riesgo' => $riesgo,
            'puntaje' => $puntaje
        ];
    }


    public function calcularRiesgoYPuntajeDominios($sumaDomnio, $divisorPromedio, $rangosRiesgo) {
        $valorSuma = $sumaDomnio ?? null;
        Log::info('valor suma dominio ',['suma'=>$valorSuma]);
        $promedio = $valorSuma ? $valorSuma / $divisorPromedio : null;
        Log::info('promedio dominio ',['promedio'=>$promedio]);
        $total = $promedio ? $promedio * 100 : null;
        Log::info('total dominio ',['total'=>$total]);
        $transformado = self::transformarNumero($total);
        Log::info('transformado dominio ',['transformado'=>$transformado]);
        $riesgo = self::determinarRiesgo($transformado, $rangosRiesgo);
        Log::info('riesgo dominio ',['riesgo'=>$riesgo]);
        $puntaje = $transformado ?? 0;
        Log::info('puntaje dominio ',['puntaje'=>$puntaje]);
    
        return (object) [
            'riesgo' => $riesgo,
            'puntaje' => $puntaje
        ];
    }

    public function calcularRiesgoYPuntajeEstres($sumas, $claveSuma, $sumaTotal, $divisorPromedio, $multiplicadorSuma,$divisorTransformado, $rangosRiesgo) {
        if($sumas != null && $claveSuma != null){
            $valorSuma = $sumas[$claveSuma] ?? null;
        }else if($sumaTotal != null){
            $valorSuma = $sumaTotal ?? null;
        }
       
        $promedio = $valorSuma ?($valorSuma / $divisorPromedio) * $multiplicadorSuma : null;
        $total = $promedio ? ($promedio/$divisorTransformado) * 100 : null;
        $transformado = self::transformarNumero($total);
        $riesgo = self::determinarRiesgo($transformado,$rangosRiesgo);
        $puntaje = $transformado ?? 0;
    
        return (object) [
            'riesgo' => $riesgo,
            'puntaje' => $puntaje
        ];
    }

    public function transformarNumero($resultado){
        if($resultado != null){
            return number_format($resultado,1,".",",");
        }else{
            return null;
        } 
    }

    public function determinarRiesgo($puntaje, $limitesRiesgo){
    
        list($limiteSR, $limiteRB, $limiteRM, $limiteRA, $limiteRMA) = $limitesRiesgo;
        switch (true) {
            case ($puntaje <= $limiteSR):
                $riesgo = 'SIN RIESGO';
                break;
            case ($puntaje <= $limiteRB):
                $riesgo = 'RIESGO BAJO';
                break;
            case ($puntaje <= $limiteRM):
                $riesgo = 'RIESGO MEDIO';
                break;
            case ($puntaje <= $limiteRA):
                $riesgo = 'RIESGO ALTO';
                break;
            case ($puntaje <= $limiteRMA):
                $riesgo = 'RIESGO MUY ALTO';
                break;
            default:
                $riesgo = 'NO APLICA';
                break;
        }
      
        return $riesgo;
    }
}