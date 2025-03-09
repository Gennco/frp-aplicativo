<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsfuerzoMentalB extends Model
{
    use HasFactory;

    protected $table = '3b';

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
        'periodo'
    ]; 
    
}
