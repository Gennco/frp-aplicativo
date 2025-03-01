<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $table = 'empleados'; 

    protected $primaryKey = 'registro';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'registro',
        'lugartrabajo',
        'nombre',
        'cargo',
        'cedula',
        'contrasena',
        'correo',
        'delaciudad',
        'deptociudad', 
        'personal',
        'areatrabajo',
        'sede',
        'nivelSeguridad',
        'fecha_subida',
        'tipoencuesta',
        'logo_empresa',
        'habilitado',
        'consentimiento',
        'fichadatos',
        'terminos',
        'adicional',
        'afrontamiento',
        'periodo',
        'procesos',
        'llave',
        'filtro'
    ];

    protected $hidden = ['contrasena'];

    public function getAuthPassword() { return $this->contrasena; }

    public function setContrasenaAttribute($value) { $this->attributes['contrasena'] = $value; }

    public function fichadato() {
        return $this->hasOne('App\Models\Fichadato','registro','registro');
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }
}