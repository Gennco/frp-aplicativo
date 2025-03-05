<?php

namespace App\Http\Util;

class UtilitariosInforme
{

     public function transformarNumero($resultado){
        if(!empty($resultado)){
            return number_format($resultado,1,".",",");
        }else{
            return null;
        } 
    }

    public function determinarRiesgo($puntaje, $limiteSR, $limiteRB, $limiteRM, $limiteRA,$limiteRMA){
        $riesgo = "NO APLICA";
        if(!empty($puntaje)){
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
        }
        return $riesgo;
    }
}