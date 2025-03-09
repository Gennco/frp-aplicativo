<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JornadaLaboralB extends Model
{
    use HasFactory;

    protected $table = '4b';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p21',
        'p22',
        'p23',
        'p24',
        'p25',
        'p26',
        'p27',
        'p28',
        'periodo'
    ];  

    
}
