<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;

    protected $table = 'opciones';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable =[
        'id',
        'nombre',
        'orden'
    ];


    public function preguntasA()
    {
        return $this->morphedByMany(PreguntaA::class, 'pregunta', 'pregunta_opcion_valor')
                    ->withPivot('valor_id');
    }

    public function preguntasB()
    {
        return $this->morphedByMany(PreguntaB::class, 'pregunta', 'pregunta_opcion_valor')
                    ->withPivot('valor_id');
    }

    public function preguntasEstres()
    {
        return $this->morphedByMany(Estres::class, 'pregunta', 'pregunta_opcion_valor')
                    ->withPivot('valor_id');
    }

    public function preguntasExtralaboral()
    {
        return $this->morphedByMany(Extralaboral::class, 'pregunta', 'pregunta_opcion_valor')
                    ->withPivot('valor_id');
    }

    public function preguntasAfrontamiento()
    {
        return $this->morphedByMany(Afrontamiento::class, 'pregunta', 'pregunta_opcion_valor')
                    ->withPivot('valor_id');
    }

    public function preguntasPersonalidad()
    {
        return $this->morphedByMany(Personalidad::class, 'pregunta', 'pregunta_opcion_valor')
                    ->withPivot('valor_id');
    }
    

    public function valor()
    {
        
       return $this->belongsToMany(Valor::class, 'pregunta_opcion_valor', 'opcion_id', 'valor_id')
       ->withPivot('pregunta_id', 'pregunta_type');
    }
}
