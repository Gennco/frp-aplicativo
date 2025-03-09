<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CondicionesAmbientalesA;
use App\Models\CargaLaboralA;
use App\Models\JornadaLaboralA;
use App\Models\TomaDecisionesA;
use App\Models\EsfuerzoMentalA;
use App\Models\ResponsabilidadesCargoA;
use App\Models\ParticipacionA;
use App\Models\RetroalimentacionA;
use App\Models\CapacitacionA;
use App\Models\RelacionJefesA;
use App\Models\RelacionColaboradoresA;
use App\Models\RendimientoTrabajoA;
use App\Models\ReconocimientoCompensacionA;
use App\Models\AtencionClienteA;
use App\Models\SoyJefeA;
use App\Models\InformeIntraA;
use App\Http\Util\UtilitariosInforme as Util;

class InformesController extends Controller
{
    public function generarInformeIntraA($fichadatos){
     
        $sumaCondicionesAmbientales = CondicionesAmbientalesA::where('registro', $fichadatos->registro)
        ->selectRaw('p1 + p2 + p3 + p4 + p5 +p6 +p7 +p8 + p9 + p10 + p11 + p12 as suma')
        ->pluck('suma')
        ->first();

        $sumaCargaLaboral1=CargaLaboralA::where('registro', $fichadatos->registro)
        ->selectRaw('p13+ p14 +p15 as suma')
        ->pluck('suma')
        ->first();

        $sumaCargaLaboral2=JornadaLaboralA::where('registro',$fichadatos->registro)
        ->selectRaw('p32 as suma')
        ->pluck('suma')
        ->first();

        $sumaCargaLaboral3=TomaDecisionesA::where('registro',$fichadatos->registro)
        ->selectRaw('p43 + p47 as suma')
        ->pluck('suma')
        ->first();
        
        $sumaCargalaboralTotal = $sumaCargaLaboral1 + $sumaCargaLaboral2 + $sumaCargaLaboral3;

        $sumaEsfuerzoMental = EsfuerzoMentalA::where('registro',$fichadatos->registro)
        ->selectRaw('p16 + p17 + p18 + p20 + 21  as suma')
        ->pluck('suma')
        ->first();

        $responsabilidadesCargo1 = EsfuerzoMentalA::where('registro',$fichadatos->registro)
        ->selectRaw('p19  as suma')
        ->pluck('suma')
        ->first();

        $responsabilidadesCargo2 = ResponsabilidadesCargoA::where('registro',$fichadatos->registro)
        ->selectRaw('p22 + p23 + p24 + p25 + 26  as suma')
        ->pluck('suma')
        ->first();

        $sumaResponsabilidadesCargoTotal = $responsabilidadesCargo1 + $responsabilidadesCargo2;

        $sumaResponsabilidadesCargoAdicional1= ResponsabilidadesCargoA::where('registro',$fichadatos->registro)
        ->selectRaw('p27 + p28 + p29 + p30   as suma')
        ->pluck('suma')
        ->first();

        $sumaResponsabilidadesCargoAdicional2= ParticipacionA::where('registro',$fichadatos->registro)
        ->selectRaw('p52  as suma')
        ->pluck('suma')
        ->first();

        $sumaResponsabilidadesCargoAdicionalTotal = $sumaResponsabilidadesCargoAdicional1 + $sumaResponsabilidadesCargoAdicional2;


        $sumaJornadaLaboral = JornadaLaboralA::where('registro',$fichadatos->registro)
        ->selectRaw('p31 + p32 + p34  as suma')
        ->pluck('suma')
        ->first();

        $sumaJornadaLaboralAdicional = JornadaLaboralA::where('registro',$fichadatos->registro)
        ->selectRaw('p35 + p36 + p37 + p38  as suma')
        ->pluck('suma')
        ->first();

        $sumaTomaDecisiones= TomaDecisionesA::where('registro',$fichadatos->registro)
        ->selectRaw('p39 + p40 + p41 + p42  as suma')
        ->pluck('suma')
        ->first();


        $tomaDecisionesAdcional= TomaDecisionesA::where('registro',$fichadatos->registro)
        ->selectRaw('p44 + p45 + p46  as suma')
        ->pluck('suma')
        ->first();

        $sumaParticipacion = ParticipacionA::where('registro',$fichadatos->registro)
        ->selectRaw('p48 + p49 + p50 + p51  as suma')
        ->pluck('suma')
        ->first();

        $sumaRetroalimentacion = RetroalimentacionA::where('registro',$fichadatos->registro)
        ->selectRaw('p53 + p54 + p55 + p56 + p57 + p58 + p59 as suma')
        ->pluck('suma')
        ->first();

        $sumaCapacitacion = CapacitacionA::where('registro',$fichadatos->registro)
        ->selectRaw('p60 + p61 + p62 as suma')
        ->pluck('suma')
        ->first();

        $sumaRelacionJefes = RelacionJefesA ::where('registro',$fichadatos->registro)
        ->selectRaw('p63 + p64 + p65 + p66 + p68 + p69 + p70 + p71 + p72 +p73 + p74 + p75 as suma')
        ->pluck('suma')
        ->first();
        
        $sumaRelacionColaboradores = RelacionColaboradoresA::where('registro',$fichadatos->registro)
        ->selectRaw('p76 + p77 + p78 + p79 + p80 + p81 + p82 + p83 + p84 +p85 + p86 + p87 + p88 + p89 as suma')
        ->pluck('suma')
        ->first();

        
        $sumaRendimientoTrabajo = RendimientoTrabajoA::where('registro',$fichadatos->registro)
        ->selectRaw('p90 + p91 + p92 + p94 as suma')
        ->pluck('suma')
        ->first();

        $sumaReconocimiento1 = ReconocimientoCompensacionA::where('registro', $fichadatos->registro)
        ->selectRaw('p95 + p102 + p103 + p104 + p105 as suma')
        ->pluck('suma')
        ->first();

        $sumaReconocimiento2 = ReconocimientoCompensacionA::where('registro', $fichadatos->registro)
        ->selectRaw('p96 + p97 + p98 + p99 + p100 + p101 as suma')
        ->pluck('suma')
        ->first();

        $atencionClientes = AtencionClienteA::where('registro', $fichadatos->registro)
        ->selectRaw('p106 + p107 + p108 + p109 + p110 + p111 + p112 + p113 + p114 as suma')
        ->pluck('suma')
        ->first();

        $sumaSoyJefe = SoyJefeA::where('registro', $fichadatos->registro)
        ->selectRaw('p115 + p116 + p117 + p118 + p119 + p120 + p121 + p122 + p123 as suma')
        ->pluck('suma')
        ->first();

        $caracteristicasLiderazgo = (!is_null($sumaRelacionJefes)/52)*100;
        $totalCaracteristicasLiderazgo = Util::transformarNumero($caracteristicasLiderazgo);
        $riesgoCaracteristicasLiderazgo = Util::determinarRiesgo($totalCaracteristicasLiderazgo, 3.8,15.4,30.8,46.2,100);

        $relacionesSociales = (!is_null($sumaRelacionColaboradores)/56)*100;
        $totalRelacionesSociales = Util::transformarNumero($relacionesSociales);
        $riesgoRelacionesSociales = Util::determinarRiesgo($totalRelacionesSociales, 5.4,16.1,25,37.5,100);
        
        $rendimiento = (!is_null($sumaRendimientoTrabajo)/20)*100;
        $totalRendimiento =Util::transformarNumero($rendimiento);
        $riesgoRendimiento = Util::determinarRiesgo($totalRendimiento, 10,25,40,55,100);

        $soyJefe = (!is_null($sumaSoyJefe)/36)*100;
        $totalSoyJefe = Util::transformarNumero($soyJefe);
        $riesgoSoyJefe = Util::determinarRiesgo($totalSoyJefe, 13.9,25,33.3,47.2,100);

        $sumaDominnioLiderazgoRelaciones = ((!is_null($totalCaracteristicasLiderazgo) + !is_null($totalRelacionesSociales) + !is_null($totalRendimiento) + !is_null($totalSoyJefe))/164)*100;
        $totalDominioLiderazgoRelaciones = Util::transformarNumero($sumaDominnioLiderazgoRelaciones);
        $riesgoDominioLiderazgoRelacionesS = Util::determinarRiesgo($totalDominioLiderazgoRelaciones, 9.1,17.7,25.6,34.8,100);

        $retroalimentacion = (!is_null($sumaRetroalimentacion)/28)*100;
        $totalRetroalimentacion =Util::transformarNumero($retroalimentacion);
        $riesgoRetroalimenatacion = Util::determinarRiesgo($totalRetroalimentacion, 0.9,10.7,21.4,39.3,100);

        $capacitacion = (!is_null($sumaCapacitacion)/12)*100;
        $totalCapacitacion = Util::transformarNumero($capacitacion);
        $riesgoCapacitacion = Util::determinarRiesgo($totalCapacitacion,0.9,16.7,33.3,50,100);

        $participacion = (!is_null($sumaParticipacion)/16)*100;
        $totalParticipacion = Util::transformarNumero($participacion);
        $riesgoParticipacion = Util::determinarRiesgo($totalParticipacion,12.5,25,37.5,50,100);

        $oportunidades = (!is_null($sumaTomaDecisiones)/16)*100;
        $totalOportunidades = Util::transformarNumero($oportunidades);
        $riesgoOportunidades = Util::determinarRiesgo($totalOportunidades,0.9,6.3,18.8,31.3,100);

        $control = (!is_null($tomaDecisionesAdcional)/12)*100;
        $totalControl = Util::transformarNumero($control);
        $riesgoControl = Util::determinarRiesgo($totalControl,8.3,25,41.7,58.3,100);

        $sumaDominioControl = ((!is_null($totalRetroalimentacion) + !is_null($totalCapacitacion) + !is_null($totalParticipacion) + !is_null($totalOportunidades) + !is_null($totalControl))/84)*100;
        $totalDominioControl = Util::transformarNumero($sumaDominioControl);
        $riesgoDominioControl = Util::determinarRiesgo($totalDominioControl, 10.7,19,29.8,40.5,100);

        $demandasAmbientales = (!is_null($sumaCondicionesAmbientales)/48)*100;
        $totalDemandasAmbientales = Util::transformarNumero($demandasAmbientales);
        $riesgoDemandasAmbientales = Util::determinarRiesgo($totalDemandasAmbientales,14.6,22.9,31.3,39.6,100);

        $demandasEmocionales = (!is_null($atencionClientes)/36)*100;
        $totalDemandasEmocionales = Util::transformarNumero($demandasEmocionales);
        $riesgoDemandasEmocionales = Util::determinarRiesgo($totalDemandasEmocionales, 16.7,25,33.3,47.2,100);

        $demandasCuantitativas = (!is_null($sumaCargalaboralTotal)/24)*100;
        $totalDemandasCuantitativas = Util::transformarNumero($demandasCuantitativas);
        $riesgoDemandasCuantitativas = Util::determinarRiesgo($totalDemandasCuantitativas,25,33.3,45.8,54.2,100);

        $influenciaTrabajo = (!is_null($sumaJornadaLaboralAdicional)/16)*100;
        $totalInfluenciaTrabajo = Util::transformarNumero($influenciaTrabajo);
        $riesgoInfluenciaTrabajo = Util::determinarRiesgo($totalInfluenciaTrabajo, 18.8,31.3,43.8,50,100);

        $exigenciasCargo = (!is_null($sumaResponsabilidadesCargoTotal)/24)*100;
        $totalExigenciasCargo = Util::transformarNumero($exigenciasCargo);
        $riesgoExigenciasCargo = Util::determinarRiesgo($totalExigenciasCargo,37.5,54.2,66.7,79.2,100);

        $demandasCargaMental = (!is_null($sumaEsfuerzoMental)/20)*100;
        $totalDemandasCargaMental = Util::transformarNumero($demandasCargaMental);
        $riesgoDemandasCargaMental = Util::determinarRiesgo($totalDemandasCargaMental, 60,70,80,90,100);

        $consistenciaRol = (!is_null($sumaResponsabilidadesCargoAdicionalTotal)/20)*100;
        $totalConsistenciaRol = Util::transformarNumero($consistenciaRol);
        $riesgoConsistenciaRol = Util::determinarRiesgo($totalConsistenciaRol,15,25,35,45,100);

        $demandasJornadaTrabajo = (!is_null($sumaJornadaLaboral)/12)*100;
        $totalDemandasJornadaTrabajo = Util::transformarNumero($demandasJornadaTrabajo);
        $riesgoDemandasTrabajo = Util::determinarRiesgo($totalDemandasJornadaTrabajo,8.3,25,33.3,50,100);

        $dominioDemandasdelTrabajo = ((!is_null($totalDemandasEmocionales) + !is_null($totalDemandasAmbientales) + !is_null($totalDemandasCuantitativas) 
        + !is_null($totalDemandasCargaMental) + !is_null($totalDemandasJornadaTrabajo) + !is_null($totalConsistenciaRol) + !is_null($totalExigenciasCargo) + !is_null($totalInfluenciaTrabajo))/200)*100;
        $totalDominioDemandasdelTrabajo = Util::transformarNumero($dominioDemandasdelTrabajo);
        $riesgoDominioDemandasdelTrabajo = Util::determinarRiesgo($totalDominioDemandasdelTrabajo,28.5,35,41.5,47.5,100);

        $recompensasDerivadas = (!is_null($sumaReconocimiento1)/20)*100;
        $totalRecompensasDerivadas = Util::transformarNumero($recompensasDerivadas);
        $riesgoRecompensasDerivadas = Util::determinarRiesgo($totalRecompensasDerivadas,0.9,5,10,20,100);

        $reconomientoCompensacion = (!is_null($sumaReconocimiento2)/24)*100;
        $totalReconomientoCompensacion = Util::transformarNumero($reconomientoCompensacion);
        $riesgoReconomientoCompensacion = Util::determinarRiesgo($totalReconomientoCompensacion, 4.2,16.7,25,37.5,100);

        $dominioRecompensas = ((!is_null($totalRecompensasDerivadas) + !is_null($totalReconomientoCompensacion))/44)*100;
        $totalDominioRecompensas = Util::transformarNumero($dominioRecompensas);
        $riesgoDominioRecompensas = Util::determinarRiesgo($totalDominioRecompensas,4.5,11.4,20.5,29.5,100);

        $intralaboralA = ((!is_null($totalDominioDemandasdelTrabajo) + !is_null($totalDominioLiderazgoRelaciones) + !is_null($totalDominioControl) + !is_null($totalDominioRecompensas))/492)*100;
        $totalIntralaboralA = Util::transformarNumero($intralaboralA);
        $riesgoTotalIntralaboralA = Util::determinarRiesgo($totalIntralaboralA,19.7,25.8,31.5,38,100);

        $data = [
            'registro'=>$fichadatos->registro,
            'informea_cedula'=>$fichadatos->cedula,
            'NumeroFolio'=>$fichadatos->NumeroFolio,
            'informea_empresa'=>$fichadatos->empresas,
            'informea_area'=>$fichadatos->cargoempresa,
            'informea_ciudadtrabajo'=>$fichadatos->lugartrabajocity,
            'informea_caracteristicasliderazgo'=>$totalCaracteristicasLiderazgo,
            'informea_relacionessociales'=>$totalRelacionesSociales,
            'informea_retroalimentacion'=>$totalRendimiento,
            'informea_relacioncolaboradores'=>$totalSoyJefe,
            'informea_totaldominioliderazgo'=>$totalDominioLiderazgoRelaciones,
            'informea_claridadrol'=>$totalRetroalimentacion,
            'informea_capacitacion'=>$totalCapacitacion,
            'informea_participacionmanejo'=>$totalParticipacion,
            'informea_oportunidadeshabilidades'=>$totalOportunidades,
            'informea_controlautonomia'=>$totalControl,
            'informea_totaldominiocontrol'=>$totalDominioControl,
            'informea_demandasambient'=>$totalDemandasAmbientales,
            'informea_demandaemocional'=>$totalDemandasEmocionales,
            'informea_demandacuantitativa'=>$totalDemandasCuantitativas,
            'informea_influenciatrabajo'=>$totalInfluenciaTrabajo,
            'informea_exigenciasresponsabilidad'=>$totalExigenciasCargo,
            'informea_demandacargamental'=>$totalDemandasCargaMental,
            'informea_consistenciarol'=>$totalConsistenciaRol,
            'informea_demandajornadatrabajo'=>$totalDemandasJornadaTrabajo,
            'informea_totaldominiodemanda'=>$totalDominioDemandasdelTrabajo,
            'informea_recompensaderivada'=>$totalRecompensasDerivadas,
            'informea_reconocimientocompensacion'=>$totalReconomientoCompensacion,
            'informea_totaldominiorecompensa'=>$totalDominioRecompensas,
            'informea_totaldominios'=>$totalIntralaboralA,
            'informea_riesgoliderazgo1'=>$riesgoCaracteristicasLiderazgo,
            'informea_riesgoliderazgo2'=>$riesgoRelacionesSociales,
            'informea_riesgoliderazgo3'=>$riesgoRendimiento,
            'informea_riesgoliderazgo4'=>$riesgoSoyJefe,
            'informea_riesgoliderazgototal'=>$riesgoDominioLiderazgoRelacionesS,
            'informea_riesgocontrol1'=>$riesgoRetroalimenatacion,
            'informea_riesgocontrol2'=>$riesgoCapacitacion,
            'informea_riesgocontrol3'=>$riesgoParticipacion,
            'informea_riesgocontrol4'=>$riesgoOportunidades,
            'informea_riesgocontrol5'=>$riesgoControl,
            'informea_riesgocontroltotal'=>$riesgoDominioControl,
            'informea_riesgodemanda1'=>$riesgoDemandasAmbientales,
            'informea_riesgodemanda2'=>$riesgoDemandasEmocionales,
            'informea_riesgodemanda3'=>$riesgoDemandasCuantitativas,
            'informea_riesgodemanda4'=>$riesgoInfluenciaTrabajo,
            'informea_riesgodemanda5'=>$riesgoExigenciasCargo,
            'informea_riesgodemanda6'=>$riesgoDemandasCargaMental,
            'informea_riesgodemanda7'=>$riesgoConsistenciaRol,    
            'informea_riesgodemanda8'=>$riesgoDemandasTrabajo,
            'informea_riesgodemandatotal'=>$riesgoDominioDemandasdelTrabajo,
            'informea_riesgorecompensa1'=>$riesgoRecompensasDerivadas,
            'informea_riesgorecompensa2'=>$riesgoReconomientoCompensacion,
            'informea_riesgorecompensatotal'=>$riesgoDominioRecompensas,
            'informea_riesgototal'=>$riesgoTotalIntralaboralA,
            'periodo'=>$fichadatos->periodo  
        ];

        try {
            $modelInfo = InformeIntraA::where('registro',$fichadatos->registro)->first();
            !empty($modelInfo) ?  InformeIntraA::where('registro',$data['registro'])->update($data) : InformeIntraA::create($data);
        } catch (Exception $exception) {
            Log::error('Error registrando informe intralabora tipo A: ', $exception);
            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar guardar sus datos');
        }
    }

}
