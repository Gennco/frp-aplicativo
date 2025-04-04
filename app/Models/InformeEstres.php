<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeEstres extends Model
{
    use HasFactory;

    protected $table = 'grupalestres1a';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'estres_cedula', 
        'estres_lugartrabajo', 
        'estres_depto',
        'estres_ciudad', 
        'estres_transformado1', 
        'estres_transformado2', 
        'estres_transformado3', 
        'estres_transformado4', 
        'estres_transformado5', 
        'estres_rta1', 
        'estres_rta2', 
        'estres_rta3', 
        'estres_rta4', 
        'estres_rta5',
        'periodo'
    ];

    /*public function getTable()
    {
        if ($user && $user->nivelSeguridad == config('constants.TIPO_B')) {
            return 'grupalestres1a';
        } 
        return $this->table;
    }*/
}