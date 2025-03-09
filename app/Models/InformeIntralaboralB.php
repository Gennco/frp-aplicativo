<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeIntralaboralB extends Model
{
    use HasFactory;
    protected $table = 'informeb';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'informeb_cedula',
        'informeb_empresa',
        'informeb_area',
        'informeb_ciudadtrabajo', 
        'informeb_caracteristicasliderazgo', 
        'informeb_relacionessociales', 
        'informeb_retroalimentacion', 
        'informeb_relacioncolaboradores', 
        'informeb_totaldominioliderazgo', 
        'informeb_claridadrol', 
        'informeb_capacitacion', 
        'informeb_participacionmanejo', 
        'informeb_oportunidadeshabilidades', 
        'informeb_controlautonomia', 
        'informeb_totaldominiocontrol', 
        'informeb_demandasambient', 
        'informeb_demandaemocional', 
        'informeb_demandacuantitativa', 
        'informeb_influenciatrabajo', 
        'informeb_exigenciasresponsabilidad', 
        'informeb_demandacargamental', 
        'informeb_consistenciarol', 
        'informeb_demandajornadatrabajo', 
        'informeb_totaldominiodemanda', 
        'informeb_recompensaderivada', 
        'informeb_reconocimientocompensacion', 
        'informeb_totaldominiorecompensa', 
        'informeb_totaldominios', 
        'informeb_riesgoliderazgo1', 
        'informeb_riesgoliderazgo2', 
        'informeb_riesgoliderazgo3', 
        'informeb_riesgoliderazgo4', 
        'informeb_riesgoliderazgototal', 
        'informeb_riesgocontrol1', 
        'informeb_riesgocontrol2', 
        'informeb_riesgocontrol3', 
        'informeb_riesgocontrol4', 
        'informeb_riesgocontrol5', 
        'informeb_riesgocontroltotal', 
        'informeb_riesgodemanda1', 
        'informeb_riesgodemanda2', 
        'informeb_riesgodemanda3', 
        'informeb_riesgodemanda4', 
        'informeb_riesgodemanda5', 
        'informeb_riesgodemanda6', 
        'informeb_riesgodemanda7', 
        'informeb_riesgodemanda8', 
        'informeb_riesgodemandatotal', 
        'informeb_riesgorecompensa1', 
        'informeb_riesgorecompensa2', 
        'informeb_riesgorecompensatotal', 
        'informeb_riesgototal',
        'periodo' 
    ];
}    