<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_municipio';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_municipio',
        'municipio',
        'estado',
        'id_departamento',
    ];

    public function departmento()
    {
        return $this->belongsTo(Departmento::class, 'id_departamento', 'id_departamento');
    }
}
