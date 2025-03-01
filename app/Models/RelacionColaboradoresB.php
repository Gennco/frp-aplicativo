<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacionColaboradoresB extends Model
{
    use HasFactory;

    protected $table = '12b';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'p62',
        'p63',
        'p64',
        'p65',
        'p66',
        'p67',
        'p68',
        'p69',
        'p70',
        'p71',
        'p72',
        'p73',
        'periodo'
    ];
}
