<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JornadaLaboralA extends Model
{
    use HasFactory;

    protected $table = 'a5';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p31',
        'p32',
        'p33',
        'p34',
        'p35',
        'p36',
        'p37',
        'p38',
        'periodo'
    ];  
}
