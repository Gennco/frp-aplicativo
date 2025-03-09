<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetroalimentacionA extends Model
{
    use HasFactory;

    protected $table = 'a8';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p53',
        'p54',
        'p55',
        'p56',
        'p57',
        'p58',
        'p59',
        'periodo'
    ];  
}
