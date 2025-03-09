<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TomaDecisionesA extends Model
{
    use HasFactory;

    protected $table = 'a6';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p39',
        'p40',
        'p41',
        'p42',
        'p43',
        'p44',
        'p45',
        'p46',
        'p47',
        'periodo'
    ];  
}
