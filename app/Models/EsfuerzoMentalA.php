<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsfuerzoMentalA extends Model
{
    use HasFactory;

    protected $table = 'a3';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p16',
        'p17',
        'p18',
        'p19',
        'p20',
        'p21',
        'periodo'
    ];  
}
