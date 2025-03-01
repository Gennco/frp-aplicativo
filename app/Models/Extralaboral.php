<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extralaboral extends Model
{
    use HasFactory;

    protected $table = 'preguntas_extralaboral';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable =[
        'id',
        'seccion_id',
        'pregunta',
        'orden'
    ];
    
    public function opciones()
    {
        return $this->belongsToMany(Opcion::class, 'pregunta_opcion_valor', 'pregunta_id', 'opcion_id')
                    ->withPivot('valor_id', 'pregunta_type')
                    ->wherePivot('pregunta_type', self::class);
    }                

    
}
