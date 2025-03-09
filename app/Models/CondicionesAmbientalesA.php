<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionesAmbientalesA extends Model
{
    use HasFactory;

    protected $table = 'a1';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
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
        'periodo'
    ];  
}
