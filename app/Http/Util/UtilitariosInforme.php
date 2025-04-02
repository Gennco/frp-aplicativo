<?php

namespace App\Http\Util;

use Illuminate\Support\Facades\Log;

class UtilitariosInforme
{
    public function calcularRiesgoYPuntajeDimensiones($sumas, $claveSuma, $divisorPromedio, $rangosRiesgo) {
       
        $valorSuma = $sumas ?  $sumas[$claveSuma] : null;
        if(!isset($sumas[$claveSuma])){
            $riesgo = "NO APLICA";
            $puntaje= 0;
        }else{
            $promedio = $valorSuma ? $valorSuma / $divisorPromedio : null;
            $total = $promedio ? $promedio * 100 : null;        
            $transformado = self::transformarNumero($total);
            $riesgo = self::determinarRiesgo($transformado, $rangosRiesgo);
            $puntaje = $transformado ?? 0;
        }  

        return (object) [
            'riesgo' => $riesgo,
            'puntaje' => $puntaje
        ];
    }


    public function calcularRiesgoYPuntajeDominios($sumaDomnio, $divisorPromedio, $rangosRiesgo) {
        $valorSuma = $sumaDomnio ?? null;
        if(is_null($valorSuma)){
            $riesgo = "NO APLICA";
            $puntaje = 0;
        }else{
            $promedio = $valorSuma ? $valorSuma / $divisorPromedio : null;
            $total = $promedio ? $promedio * 100 : null;
            $transformado = self::transformarNumero($total);
            $riesgo = self::determinarRiesgo($transformado, $rangosRiesgo);
            $puntaje = $transformado ?? 0;
        }    
        return (object) [
            'riesgo' => $riesgo,
            'puntaje' => $puntaje
        ];
    }

    public function calcularRiesgoYPuntajeEstres($sumas, $claveSuma, $sumaTotal, $divisorPromedio, $multiplicadorSuma,$divisorTransformado, $rangosRiesgo) {
        if($sumas != null && $claveSuma != null){
            $valorSuma = $sumas[$claveSuma];
        }else if($sumaTotal != null){
            $valorSuma = $sumaTotal;
        }else{
            $valorSuma = 0;
        }

        $promedio = $valorSuma ? ($valorSuma / $divisorPromedio) * $multiplicadorSuma : null;
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
        if(!empty($resultado)){
            return number_format($resultado,1,".",",");
        }
        else{
            return null;
        } 
    }

    public function determinarRiesgo($puntaje, $limitesRiesgo){
        list($limiteSR, $limiteRB, $limiteRM, $limiteRA, $limiteRMA) = $limitesRiesgo;
       
        switch (true) {
            case ($puntaje === 0.0) :
                $riesgo = 'SIN RIESGO';
                break;
            case ($puntaje === 0) :
                $riesgo = 'SIN RIESGO';
                break;    
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
                $riesgo = "NO APLICA";
        }
      
        return $riesgo;
    }
}