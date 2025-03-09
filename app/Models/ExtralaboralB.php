<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtralaboralB extends Model
{
    use HasFactory;

    protected $table = 'ext2';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'ext14',
        'ext15',
        'ext16',
        'ext17',
        'ext18',
        'ext19',
        'ext20',
        'ext21',
        'ext22',
        'ext23',
        'ext24',
        'ext25',
        'ext26',
        'ext27',
        'ext28',
        'ext29',
        'ext30',
        'ext31',
        'periodo'
    ];
    
    public function getTable()
    {
        // Determine the table name based on the user's role
        if (auth()->user()->nivelSeguridad == config('constants.TIPO_B')) {
            return 'ext2a';
        }

        return $this->table;
    }
}
