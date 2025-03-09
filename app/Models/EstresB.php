<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstresB extends Model
{
    use HasFactory;


    protected $table = 'estres1a';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'estres1a',
        'estres2a',
        'estres3a',
        'estres4a',
        'estres5a',
        'estres6a',
        'estres7a',
        'estres8a',
        'estres9a',
        'estres10a',
        'estres11a',
        'estres12a',
        'estres13a',
        'estres14a',
        'estres15a',
        'estres16a',
        'estres17a',
        'estres18a',
        'estres19a',
        'estres20a',
        'estres21a',
        'estres22a',
        'estres23a',
        'estres24a',
        'estres25a',
        'estres26a',
        'estres27a',
        'estres28a',
        'estres29a',
        'estres30a',
        'estres31a',
        'periodo'
    ];  

}
