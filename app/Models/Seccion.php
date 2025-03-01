<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'tipo',
        'route',
        'rango',
        'orden', 
        'nombre',
        'enunciado',
        'modelo',
        'modeloPregunta'
    ];

    public function preguntas($tipo = null)
    {
        switch ($tipo) {
            case 'A':
                return $this->hasMany(PreguntaA::class, 'seccion_id');
            case 'B':
                return $this->hasMany(PreguntaB::class, 'seccion_id');   
            default:
                return null;
        }
    }
}
