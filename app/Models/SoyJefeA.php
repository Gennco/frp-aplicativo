<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoyJefeA extends Model
{
    use HasFactory;

    protected $table = 'a15';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p115',
        'p116',
        'p117',
        'p118',
        'p119',
        'p120',
        'p121',
        'p122',
        'p123',
        'periodo'
    ];  
}
