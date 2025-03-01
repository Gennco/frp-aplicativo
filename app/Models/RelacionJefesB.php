<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacionJefesB extends Model
{
    use HasFactory;

    protected $table = '10b';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p49',
        'p50',
        'p51',
        'p52',
        'p53',
        'p54',
        'p55',
        'p56',
        'p57',
        'p58',
        'p59',
        'p60',
        'p61',
        'periodo'
    ];
}
