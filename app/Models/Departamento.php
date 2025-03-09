<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_departamento';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'departamento',
    ];  
    
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_departamento', 'id_departamento');
    }
}
