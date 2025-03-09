<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconocimientoCompensacionA extends Model
{
    use HasFactory;

    protected $table = 'a13';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p95',
        'p96',
        'p97',
        'p98',
        'p99',
        'p100',
        'p101',
        'p102',
        'p103',
        'p104',
        'p105',
        'periodo'
    ];  
}
