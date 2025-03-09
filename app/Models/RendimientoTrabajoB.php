<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendimientoTrabajoB extends Model
{
    use HasFactory;

    protected $table = '14b';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p74',
        'p75',
        'p76',
        'p77',
        'p78',
        'periodo'
    ];

}
