<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionClienteA extends Model
{
    use HasFactory;

    protected $table = 'a14';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p105',
        'p106',
        'p107',
        'p108',
        'p109',
        'p110',
        'p111',
        'p112',
        'p113',
        'p114',
        'periodo'
    ];  
}
