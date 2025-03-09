<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionA extends Model
{
    use HasFactory;

    protected $table = 'a7';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p48',
        'p49',
        'p50',
        'p51',
        'p52',
        'periodo'
    ];  
}
