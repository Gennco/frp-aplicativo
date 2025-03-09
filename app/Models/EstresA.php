<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstresA extends Model
{
    use HasFactory;


    protected $table = 'estres1';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'estres1',
        'estres2',
        'estres3',
        'estres4',
        'estres5',
        'estres6',
        'estres7',
        'estres8',
        'estres9',
        'estres10',
        'estres11',
        'estres12',
        'estres13',
        'estres14',
        'estres15',
        'estres16',
        'estres17',
        'estres18',
        'estres19',
        'estres20',
        'estres21',
        'estres22',
        'estres23',
        'estres24',
        'estres25',
        'estres26',
        'estres27',
        'estres28',
        'estres29',
        'estres30',
        'estres31',
        'periodo'
    ];  

    public function getTable()
    {
        // Determine the table name based on the user's role
        if (auth()->user()->nivelSeguridad == config('constants.TIPO_B')) {
            return 'estres1a';
        }

        return $this->table;
    }
}
