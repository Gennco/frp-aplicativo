<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeExtralaboral extends Model
{
    use HasFactory;

    protected $table = 'grupalextralaboral1';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio', 
        'registro', 
        'extra_cedula', 
        'extra_lugartrabajo',
        'extra_depto',
        'extra_ciudad', 
        'extra_transformado1', 
        'extra_respuesta1', 
        'extra_transformado2', 
        'extra_respuesta2', 
        'extra_transformado3',
        'extra_respuesta3', 
        'extra_transformado4',
        'extra_respuesta4', 
        'extra_transformado5', 
        'extra_respuesta5', 
        'extra_transformado6', 
        'extra_respuesta6', 
        'extra_transformado7', 
        'extra_respuesta7', 
        'extra_transformadototal', 
        'extra_respuestatotal', 
        'periodo'
    ];

    public function getTable()
    {
        if (auth()->user()->nivelSeguridad == config('constants.TIPO_B')) {
            return 'grupalextralaboral1a';
        }

        return $this->table;
    }
}    