<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfrontamientoC extends Model
{
    use HasFactory;

    protected $table = 'afrontamientoc';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'nombre',
        'sede',
        'area',
        'cargo',
        'p1',
        'p2',
        'p3',
        'p4',
        'p5',
        'p6',
        'p7',
        'p8',
        'p9',
        'p10',
        'p11',
        'p12',
        'p13',
        'p14',
        'p15',
        'p16',
        'p17',
        'p18',
        'p19',
        'p20',
        'p21',
        'p22',
        'p23',
        'periodo'
    ];  
}
