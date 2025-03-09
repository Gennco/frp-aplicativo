<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconocimientoCompensacionB extends Model
{
    use HasFactory;

    protected $table = '15b';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p79',
        'p80',
        'p81',
        'p82',
        'p83',
        'p84',
        'p85',
        'p86',
        'p87',
        'p88',
        'periodo'
    ];
}
