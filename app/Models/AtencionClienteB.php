<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionClienteB extends Model
{
    use HasFactory;

    protected $table = '16b';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p89',
        'p90',
        'p91',
        'p92',
        'p93',
        'p94',
        'p95',
        'p96',
        'p97',
        'periodo'
    ]; 
}
