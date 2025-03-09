<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendimientoTrabajoA extends Model
{
    use HasFactory;

    protected $table = 'a12';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p90',
        'p91',
        'p92',
        'p93',
        'p94',
        'periodo'
    ];   
}
