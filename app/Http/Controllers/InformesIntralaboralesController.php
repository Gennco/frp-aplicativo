<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformeIntralaboralA;
use App\Models\InformeIntralaboralB;
use App\Http\Util\UtilitariosInforme as Util;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InformesIntralaboralesController extends Controller
{
    public function generarInformeIntraA($fichadatos){
        $sumas = DB::table('a10')
        ->join('a11', 'a10.registro', '=', 'a11.registro')
        ->join('a12', 'a10.registro', '=', 'a12.registro')
        ->join('a8', 'a10.registro', '=', 'a8.registro')
        ->join('a9', 'a10.registro', '=', 'a9.registro')
        ->join('a7', 'a10.registro', '=', 'a7.registro')
        ->join('a6', 'a10.registro', '=', 'a6.registro')
        ->join('a1', 'a10.registro', '=', 'a1.registro')
        ->join('a2', 'a10.registro', '=', 'a2.registro')
        ->join('a5', 'a10.registro', '=', 'a5.registro')
        ->join('a3', 'a10.registro', '=', 'a3.registro')
        ->join('a4', 'a10.registro', '=', 'a4.registro')
        ->join('a13', 'a10.registro', '=', 'a13.registro')
        ->where('a10.registro', $fichadatos->registro)
        ->select(
            DB::raw('(p63 + p64 + p65 + p66 + p67 + p68 + p69 + p70 + p71 + p72 + p73 + p74 + p75) AS suma1'),
            DB::raw('(p76 + p77 + p78 + p79 + p80 + p81 + p82 + p83 + p84 + p85 + p86 + p87 + p88 + p89) AS suma2'),
            DB::raw('(p90 + p91 + p92 + p93 + p94) AS suma3'),
            DB::raw('(p53 + p54 + p55 + p56 + p57 + p58 + p59) AS suma5'),
            DB::raw('(p60 + p61 + p62) AS suma6'),
            DB::raw('(p48 + p49 + p50 + p51) AS suma7'),
            DB::raw('(p39 + p40 + p41 + p42) AS suma8'),
            DB::raw('(p44 + p45 + p46) AS suma9'),
            DB::raw('(p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8+ p9 + p10 + p11 + p12) AS suma10'),
            DB::raw('(p13 + p14 + p15 + p32 + p43 + p47) AS suma12'),
            DB::raw('(p35 + p36 + p37 + p38) AS suma13'),
            DB::raw('(p19 + p22 + p23 + p24 + p25 + p26) AS suma14'),
            DB::raw('(p16 + p17 + p18 + p20 +p21) AS suma15'),
            DB::raw('(p27 + p28+ p29 + p30 + p52) AS suma16'),
            DB::raw('(p31 + p33 + p34) AS suma17'),
            DB::raw('(p95 + p102 + p103 + p104 + p105) AS suma18'),
            DB::raw('(p96 + p97 + p98 + p99 + p100 + p101) AS suma19'),
        )->get()
        ->mapWithKeys(function ($item) {
            return [
                'suma1' => $item->suma1,
                'suma2' => $item->suma2,
                'suma3' => $item->suma3,
                'suma5' => $item->suma5,
                'suma6' => $item->suma6,
                'suma7' => $item->suma7,
                'suma8' => $item->suma8,
                'suma9' => $item->suma9,
                'suma10' => $item->suma10,
                'suma12' => $item->suma12,
                'suma13' => $item->suma13,
                'suma14' => $item->suma14,
                'suma15' => $item->suma15,
                'suma16' => $item->suma16,
                'suma17' => $item->suma17,
                'suma18' => $item->suma18,
                'suma19' => $item->suma19,
            ];
        })
        ->toArray();

        $sumas11 = DB::table('a14')
        ->where('a14.registro', $fichadatos->registro)
        ->select(
            DB::raw('(p106 + p107 + p108 + p109 + p110 + p111 + p112 + p113 + p114) AS suma11'),
        )->get()
        ->mapWithKeys(function ($item) {
            return [
                'suma11' => $item->suma11, 
            ];
        })
        ->toArray();
       
        $sumas4 = DB::table('a15')
        ->where('a15.registro', $fichadatos->registro)
        ->select(
            DB::raw('(p115 + p116 + p117 + p118+ p119 + p120 + p121 + p122 + p123) AS suma4'),
        )->get()
        ->mapWithKeys(function ($item) {
            return [
                'suma4' => $item->suma4, 
            ];
        })
        ->toArray();
     
        $caracteristicasLiderazgo = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma1',52,[3.8, 15.4, 30.8, 46.2, 100]);

        $relacionesSociales =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma2',56,[5.4,16.1,25,37.5,100]);

        $retroalimentacion = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma3',20,[10,25,40,55,100]);
        
        $realcionColaboradores = Util::calcularRiesgoYPuntajeDimensiones($sumas4,'suma4',36,[13.9,25,33.3,47.2,100]);
     
        $sumaDominioCaracteristicasLiderzgo = ($sumas['suma1'] ?? 0) + ($sumas['suma2'] ?? 0) + ($sumas['suma3']  ?? 0) + ($sumas4['suma4']  ?? 0) ;        
        $dominioRelacionesCaracteristicasLIderazgo = Util::calcularRiesgoYPuntajeDominios($sumaDominioCaracteristicasLiderzgo,164,[9.1,17.7,25.6,34.8,100]);

        $claridadRol =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma5',28,[0.9,10.7,21.4,39.3,100]);

        $capacitacion =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma6',12,[0.9,16.7,33.3,50,100]);
        
        $participacion = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma7',16,[12.5,25,37.5,50,100]);

        $oportunidades = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma8',16,[0.9,6.3,18.8,31.3,100]);

        $control = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma9',12,[8.3,25,41.7,58.3,100]);
    
        $sumaDominioControlAutonomia = ($sumas['suma5'] ?? 0) + ($sumas['suma6'] ?? 0) + ($sumas['suma7'] ?? 0) + ($sumas['suma8'] ?? 0) + ($sumas['suma9'] ?? 0);
        $dominioControlAutonomia = Util::calcularRiesgoYPuntajeDominios($sumaDominioControlAutonomia,84,[10.7,19,29.8,40.5,100]);

        $demandasAmbientales = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma10',48,[14.6,22.9,31.3,39.6,100]);
        
        $demandasEmocionales =  Util::calcularRiesgoYPuntajeDimensiones($sumas11,'suma11',36,[16.7,25,33.3,47.2,100]);

        $demandasCuantitativas =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma12',24,[25,33.3,45.8,54.2,100]);

        $influenciaTrabajo = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma13',16,[18.8,31.3,43.8,50,100]);

        $exigenciasCargo = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma14',24,[37.5,54.2,66.7,79.2,100]);
        
        $demandasCargaMental = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma15',20,[60,70,80,90,100]);
        
        $consistenciaRol =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma16',20,[15,25,35,45,100]);       
        
        $demandasJornada = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma17',12,[8.3,25,33.3,50,100]);

        $sumaDominioDemandas = ($sumas['suma10'] ?? 0) + ($sumas11['suma11'] ?? 0) + ($sumas['sumas12'] ?? 0)
        + ($sumas['suma13'] ?? 0) + ($sumas['suma14'] ?? 0) + ($sumas['suma15'] ?? 0) +  + ($sumas['suma16'] ?? 0) + ($sumas['suma17'] ?? 0);
        $dominioDemandasTrabajo = Util::calcularRiesgoYPuntajeDominios($sumaDominioDemandas,200,[28.5,35,41.5,47.5,100]);

        $recompensasDerivadas = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma18',20,[0.9,5,10,20,100]);
        $reconomientoCompensacion = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma19',24,[4.2,16.7,25,37.5,100]);

        $sumaDominioRecompensas = ($sumas['suma18'] ?? 0) + ($sumas['suma19'] ?? 0);
        $dominioRecompensas = Util::calcularRiesgoYPuntajeDominios($sumaDominioRecompensas,44,[4.5,11.4,20.5,29.5,100]);
        
        $sumaDominios = ($sumaDominioCaracteristicasLiderzgo ?? 0) + ($sumaDominioControlAutonomia ?? 0) + ($sumaDominioDemandas ?? 0) + ($sumaDominioRecompensas ?? 0);
        $totalIntraA =  Util::calcularRiesgoYPuntajeDominios($sumaDominios,492,[19.7,25.8,31.5,38.0,100]);       
        
        $data = [ 
            'NumeroFolio' => $fichadatos->NumeroFolio,
            'registro'=>$fichadatos->registro,
            'informea_cedula'=>$fichadatos->cedula,
            'informea_empresa'=>$fichadatos->empresas,
            'informea_area'=>$fichadatos->cargoempresa,
            'informea_ciudadtrabajo'=>$fichadatos->lugartrabajocity, 
            'informea_caracteristicasliderazgo'=>$caracteristicasLiderazgo->puntaje, 
            'informea_relacionessociales'=>$relacionesSociales->puntaje, 
            'informea_retroalimentacion'=>$retroalimentacion->puntaje, 
            'informea_relacioncolaboradores'=>$realcionColaboradores->puntaje, 
            'informea_totaldominioliderazgo'=>$dominioRelacionesCaracteristicasLIderazgo->puntaje, 
            'informea_claridadrol'=>$claridadRol->puntaje, 
            'informea_capacitacion'=>$capacitacion->puntaje, 
            'informea_participacionmanejo'=>$participacion->puntaje, 
            'informea_oportunidadeshabilidades'=>$oportunidades->puntaje, 
            'informea_controlautonomia'=>$control->puntaje, 
            'informea_totaldominiocontrol'=>$dominioControlAutonomia->puntaje, 
            'informea_demandasambient'=>$demandasAmbientales->puntaje, 
            'informea_demandaemocional'=>$demandasEmocionales->puntaje, 
            'informea_demandacuantitativa'=>$demandasCuantitativas->puntaje, 
            'informea_influenciatrabajo'=>$influenciaTrabajo->puntaje, 
            'informea_exigenciasresponsabilidad'=>$exigenciasCargo->puntaje, 
            'informea_demandacargamental'=>$demandasCargaMental->puntaje, 
            'informea_consistenciarol'=>$consistenciaRol->puntaje, 
            'informea_demandajornadatrabajo'=>$demandasJornada->puntaje, 
            'informea_totaldominiodemanda'=>$dominioDemandasTrabajo->puntaje, 
            'informea_recompensaderivada'=>$recompensasDerivadas->puntaje, 
            'informea_reconocimientocompensacion'=>$reconomientoCompensacion->puntaje, 
            'informea_totaldominiorecompensa'=>$dominioRecompensas->puntaje, 
            'informea_totaldominios'=>$totalIntraA->puntaje, 
            'informea_riesgoliderazgo1'=>$caracteristicasLiderazgo->riesgo, 
            'informea_riesgoliderazgo2'=>$relacionesSociales->riesgo, 
            'informea_riesgoliderazgo3'=>$realcionColaboradores->riesgo,
            'informea_riesgoliderazgo4'=>$retroalimentacion->riesgo, 
            'informea_riesgoliderazgototal'=>$dominioRelacionesCaracteristicasLIderazgo->riesgo, 
            'informea_riesgocontrol1'=>$claridadRol->riesgo, 
            'informea_riesgocontrol2'=>$capacitacion->riesgo, 
            'informea_riesgocontrol3'=>$participacion->riesgo, 
            'informea_riesgocontrol4'=>$oportunidades->riesgo, 
            'informea_riesgocontrol5'=>$control->riesgo, 
            'informea_riesgocontroltotal'=>$dominioControlAutonomia->riesgo, 
            'informea_riesgodemanda1'=>$demandasAmbientales->riesgo, 
            'informea_riesgodemanda2'=>$demandasEmocionales->riesgo, 
            'informea_riesgodemanda3'=>$demandasCuantitativas->riesgo, 
            'informea_riesgodemanda4'=>$influenciaTrabajo->riesgo, 
            'informea_riesgodemanda5'=>$exigenciasCargo->riesgo, 
            'informea_riesgodemanda6'=>$demandasCargaMental->riesgo, 
            'informea_riesgodemanda7'=>$consistenciaRol->riesgo,
            'informea_riesgodemanda8'=>$demandasJornada->riesgo, 
            'informea_riesgodemandatotal'=>$dominioDemandasTrabajo->riesgo, 
            'informea_riesgorecompensa1'=>$recompensasDerivadas->riesgo, 
            'informea_riesgorecompensa2'=>$reconomientoCompensacion->riesgo, 
            'informea_riesgorecompensatotal'=>$dominioRecompensas->riesgo, 
            'informea_riesgototal'=>$totalIntraA->riesgo,
            'periodo'=>$fichadatos->periodo 
        ];
        try {
            $modelInfo = InformeIntralaboralA::where('registro',$fichadatos->registro)->first();
            !empty($modelInfo) ?  InformeIntralaboralA::where('registro',$data['registro'])->update($data) : InformeIntralaboralA::create($data);
        } catch (Exception $exception) {
            Log::error('Error registrando informe intralabora tipo A: ', $exception);
            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }
    }

    public function generarInformeIntraB($fichadatos){
        $sumas = DB::table('10b')
        ->join('12b', '10b.registro', '=', '12b.registro')
        ->join('14b', '10b.registro', '=', '14b.registro')
        ->join('8b', '10b.registro', '=', '8b.registro')
        ->join('9b', '10b.registro', '=', '9b.registro')
        ->join('7b', '10b.registro', '=', '7b.registro')
        ->join('5b', '10b.registro', '=', '5b.registro')
        ->join('1b', '10b.registro', '=', '1b.registro')
        ->join('2b', '10b.registro', '=', '2b.registro')
        ->join('4b', '10b.registro', '=', '4b.registro')
        ->join('3b', '10b.registro', '=', '3b.registro')
        ->join('15b', '10b.registro', '=', '15b.registro')
        ->where('10b.registro', $fichadatos->registro)
        ->select(
            DB::raw('(p49 + p50 + p51 + p52 + p53 + p54 + p55 + p56 + p57 + p58 + p59 + p60 + p61) AS suma1'),
            DB::raw('(p62 + p63 + p64 + p65 + p66 + p67 + p68 + p69 + p70 + p71 + p72 + p73) AS suma2'),
            DB::raw('(p74 + p75 + p76 + p77 + p78) AS suma3'),
            DB::raw('(p41 + p42 + p43 + p44 + p45) AS suma4'),
            DB::raw('(p46 + p47 + p48) AS suma5'),
            DB::raw('(p38 + p39 + p40) AS suma6'),
            DB::raw('(p29 + p30 + p31 + p32) AS suma7'),
            DB::raw('(p34 + p35 + p36) AS suma8'),
            DB::raw('(p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9 + p10 + p11 + p12) AS suma9'),
            DB::raw('(p13 + p14 + p15) AS suma10'),
            DB::raw('(p25 + p26 + p27 + p28) AS suma11'),
            DB::raw('(p16 + p17 + p18 + p19 + p20) AS suma12'),
            DB::raw('(p21 + p22 + p23 + p24 + p33 + p37) AS suma13'),
            DB::raw('(p85 + p86 + p87 + p88) AS suma14'),
            DB::raw('(p79 + p80 + p81 + p82 + p83 + p84) AS suma15')
        )->get()
        ->mapWithKeys(function ($item) {
            return [
                'suma1' => $item->suma1,
                'suma2' => $item->suma2,
                'suma3' => $item->suma3,
                'suma4' => $item->suma4,
                'suma5' => $item->suma5,
                'suma6' => $item->suma6,
                'suma7' => $item->suma7,
                'suma8' => $item->suma8,
                'suma9' => $item->suma9,
                'suma10' => $item->suma10,
                'suma11' => $item->suma11,
                'suma12' => $item->suma12,
                'suma13' => $item->suma13,
                'suma14' => $item->suma14,
                'suma15' => $item->suma15
            ];
        })
        ->toArray();

        $sumas16 = DB::table('16b')
        ->where('16b.registro', $fichadatos->registro)
        ->select(
            DB::raw('(p89 + p90 + p91 + p92 + p93 + p94 + p95 + p96 +p97) AS suma16')
        )->get()
        ->mapWithKeys(function ($item) {
            return [
                'suma16' => $item->suma16,
            ];
        })
        ->toArray();

        $caracteristicasLiderazgo = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma1',52,[3.8, 13.5, 25, 38.5, 100]);

        $relacionesSociales =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma2',48,[6.3,14.6,27.1,37.5,100]);

        $retroalimentacion = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma3',20,[5,20,30,50,100]);

        $sumaDominioCaracteristicasLiderzgo = ($sumas['suma1'] ?? 0) + ($sumas['suma2'] ?? 0) + ($sumas['suma3'] ?? 0);        
        $dominioRelacionesCaracteristicasLIderazgo = Util::calcularRiesgoYPuntajeDominios($sumaDominioCaracteristicasLiderzgo,120,[8.3,17.5,26.7,38.3,100]);

        $claridadRol =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma4',20,[0.9,5,15,30,100]);

        $capacitacion =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma5',12,[0.9,16.7,25,50,100]);
        
        $participacion = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma6',12,[16.7,33.3,41.7,58.3,100]);

        $oportunidades = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma7',16,[12.5,25,37.5,56.3,100]);

        $control = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma8',12,[33.3,50,66.7,75,100]);
    
        $sumaDominioControlAutonomia = ($sumas['suma4'] ?? 0) + ($sumas['suma5'] ?? 0) + ($sumas['suma6'] ?? 0) + ($sumas['suma7'] ?? 0) + ($sumas['suma8'] ?? 0);
        $dominioControlAutonomia = Util::calcularRiesgoYPuntajeDominios($sumaDominioControlAutonomia,72,[19.4,26.4,34.7,43.1,100]);

        $demandasAmbientales = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma9',48,[29.9,31.3,39.6,47.9,100]);
        
        $demandasEmocionales =  Util::calcularRiesgoYPuntajeDimensiones($sumas16,'suma16',36,[19.4,27.8,38.9,47.2,100]);

        $demandasCuantitativas =  Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma10',12,[16.7,33.3,41.7,50,100]);

        $influenciaTrabajo = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma11',16,[12.5,25,31.3,50,100]);

        $demandasCargaMental = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma12',20,[50,65,75,85,100]);

        $demandasJornada = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma13',24,[25,37.5,45.8,58.3,100]);

        $sumaDominioDemandas = ($sumas['suma9'] ?? 0) + ($sumas16['suma16'] ?? 0) + ($sumas['sumas10'] ?? 0)
        + ($sumas['suma11'] ?? 0) + ($sumas['suma12'] ?? 0) + ($sumas['suma13'] ?? 0);
        $dominioDemandasTrabajo = Util::calcularRiesgoYPuntajeDominios($sumaDominioDemandas,156,[26.9,33.3,37.8,44.2,100]);

        $recompensasDerivadas = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma14',16,[0.9,6.3,12.5,18.8,100]);
        $reconomientoCompensacion = Util::calcularRiesgoYPuntajeDimensiones($sumas,'suma15',24,[0.9,12.5,25,37.5,100]);

        $sumaDominioRecompensas = ($sumas['suma14'] ?? 0) + ($sumas['suma15'] ?? 0);
        $dominioRecompensas = Util::calcularRiesgoYPuntajeDominios($sumaDominioRecompensas,40,[2.5,10,17.5,27.5,100]);
        
        $sumaDominios = ($sumaDominioCaracteristicasLiderzgo ?? 0) + ($sumaDominioControlAutonomia ?? 0) + ($sumaDominioDemandas ?? 0) + ($sumaDominioRecompensas ?? 0);
        $totalIntraB =  Util::calcularRiesgoYPuntajeDominios($sumaDominios,388,[20.6,26,31.2,38.7,100]);
       
        $data = [ 
            'NumeroFolio' => $fichadatos->NumeroFolio,
            'registro'=>$fichadatos->registro,
                'informeb_cedula'=>$fichadatos->cedula,
            'informeb_empresa'=>$fichadatos->empresas,
            'informeb_area'=>$fichadatos->cargoempresa,
            'informeb_ciudadtrabajo'=>$fichadatos->lugartrabajocity, 
            'informeb_caracteristicasliderazgo'=>$caracteristicasLiderazgo->puntaje, 
            'informeb_relacionessociales'=>$relacionesSociales->puntaje, 
            'informeb_retroalimentacion'=>$retroalimentacion->puntaje, 
            'informeb_relacioncolaboradores'=>0, 
            'informeb_totaldominioliderazgo'=>$dominioRelacionesCaracteristicasLIderazgo->puntaje, 
            'informeb_claridadrol'=>$claridadRol->puntaje, 
            'informeb_capacitacion'=>$capacitacion->puntaje, 
            'informeb_participacionmanejo'=>$participacion->puntaje, 
            'informeb_oportunidadeshabilidades'=>$oportunidades->puntaje, 
            'informeb_controlautonomia'=>$control->puntaje, 
            'informeb_totaldominiocontrol'=>$dominioControlAutonomia->puntaje, 
            'informeb_demandasambient'=>$demandasAmbientales->puntaje, 
            'informeb_demandaemocional'=>$demandasEmocionales->puntaje, 
            'informeb_demandacuantitativa'=>$demandasCuantitativas->puntaje, 
            'informeb_influenciatrabajo'=>$influenciaTrabajo->puntaje, 
            'informeb_exigenciasresponsabilidad'=>0, 
            'informeb_demandacargamental'=>$demandasCargaMental->puntaje, 
            'informeb_consistenciarol'=>0, 
            'informeb_demandajornadatrabajo'=>$demandasJornada->puntaje, 
            'informeb_totaldominiodemanda'=>$dominioDemandasTrabajo->puntaje, 
            'informeb_recompensaderivada'=>$recompensasDerivadas->puntaje, 
            'informeb_reconocimientocompensacion'=>$reconomientoCompensacion->puntaje, 
            'informeb_totaldominiorecompensa'=>$dominioRecompensas->puntaje, 
            'informeb_totaldominios'=>$totalIntraB->puntaje, 
            'informeb_riesgoliderazgo1'=>$caracteristicasLiderazgo->riesgo, 
            'informeb_riesgoliderazgo2'=>$relacionesSociales->riesgo, 
            'informeb_riesgoliderazgo3'=>"NO APLICA",
            'informeb_riesgoliderazgo4'=>$retroalimentacion->riesgo,
            'informeb_riesgoliderazgototal'=>$dominioRelacionesCaracteristicasLIderazgo->riesgo, 
            'informeb_riesgocontrol1'=>$claridadRol->riesgo, 
            'informeb_riesgocontrol2'=>$capacitacion->riesgo, 
            'informeb_riesgocontrol3'=>$participacion->riesgo, 
            'informeb_riesgocontrol4'=>$oportunidades->riesgo, 
            'informeb_riesgocontrol5'=>$control->riesgo, 
            'informeb_riesgocontroltotal'=>$dominioControlAutonomia->riesgo, 
            'informeb_riesgodemanda1'=>$demandasAmbientales->riesgo, 
            'informeb_riesgodemanda2'=>$demandasEmocionales->riesgo, 
            'informeb_riesgodemanda3'=>$demandasCuantitativas->riesgo, 
            'informeb_riesgodemanda4'=>$influenciaTrabajo->riesgo, 
            'informeb_riesgodemanda5'=>"NO APLICA", 
            'informeb_riesgodemanda6'=>$demandasCargaMental->riesgo, 
            'informeb_riesgodemanda7'=>"NO APLICA",
            'informeb_riesgodemanda8'=>$demandasJornada->riesgo, 
            'informeb_riesgodemandatotal'=>$dominioDemandasTrabajo->riesgo, 
            'informeb_riesgorecompensa1'=>$recompensasDerivadas->riesgo, 
            'informeb_riesgorecompensa2'=>$reconomientoCompensacion->riesgo, 
            'informeb_riesgorecompensatotal'=>$dominioRecompensas->riesgo, 
            'informeb_riesgototal'=>$totalIntraB->riesgo,
            'periodo'=>$fichadatos->periodo 
        ];    

        try {
            $modelInfo = InformeIntralaboralB::where('registro',$fichadatos->registro)->first();
            !empty($modelInfo) ?  InformeIntralaboralB::where('registro',$data['registro'])->update($data) : InformeIntralaboralB::create($data);
        } catch (Exception $exception) {
            Log::error('Error registrando informe intralabora tipo B: ', $exception);
            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }

    }


}

