<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtralaboralA extends Model
{
    use HasFactory;

    protected $table = 'ext1';

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'NumeroFolio',
        'registro',
        'empresa',
        'cedula',
        'ext1',
        'ext2',
        'ext3',
        'ext4',
        'ext5',
        'ext6',
        'ext7',
        'ext8',
        'ext9',
        'ext10',
        'ext11',
        'ext12',
        'ext13',
        'periodo'
    ];  


    public function getTable()
    {
        // Determine the table name based on the user's role
        if (auth()->user()->nivelSeguridad == config('constants.TIPO_B')) {
            return 'ext1a';
        }

        return $this->table;
    }
}
