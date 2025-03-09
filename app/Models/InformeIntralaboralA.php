<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeIntralaboralA extends Model
{
    use HasFactory;
    protected $table = 'informea';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'registro',
        'informea_cedula',
        'NumeroFolio',
        'informea_empresa',
        'informea_area',
        'informea_ciudadtrabajo',
        'informea_caracteristicasliderazgo',
        'informea_relacionessociales',
        'informea_retroalimentacion',
        'informea_relacioncolaboradores',
        'informea_totaldominioliderazgo',
        'informea_claridadrol',
        'informea_capacitacion',
        'informea_participacionmanejo',
        'informea_oportunidadeshabilidades',
        'informea_controlautonomia',
        'informea_totaldominiocontrol',
        'informea_demandasambient',
        'informea_demandaemocional',
        'informea_demandacuantitativa',
        'informea_influenciatrabajo',
        'informea_exigenciasresponsabilidad',
        'informea_demandacargamental',
        'informea_consistenciarol',
        'informea_demandajornadatrabajo',
        'informea_totaldominiodemanda',
        'informea_recompensaderivada',
        'informea_reconocimientocompensacion',
        'informea_totaldominiorecompensa',
        'informea_totaldominios',
        'informea_riesgoliderazgo1',
        'informea_riesgoliderazgo2',
        'informea_riesgoliderazgo3',
        'informea_riesgoliderazgo4',
        'informea_riesgoliderazgototal',
        'informea_riesgocontrol1',
        'informea_riesgocontrol2',
        'informea_riesgocontrol3',
        'informea_riesgocontrol4',
        'informea_riesgocontrol5',
        'informea_riesgocontroltotal',
        'informea_riesgodemanda1',
        'informea_riesgodemanda2',
        'informea_riesgodemanda3',
        'informea_riesgodemanda4',
        'informea_riesgodemanda5',
        'informea_riesgodemanda6',
        'informea_riesgodemanda7',    
        'informea_riesgodemanda8',
        'informea_riesgodemandatotal',
        'informea_riesgorecompensa1',
        'informea_riesgorecompensa2',
        'informea_riesgorecompensatotal',
        'informea_riesgototal',
        'periodo'  
    ];  
}
