<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeePasswords extends Command
{
    /**
     * El nombre del comando que se ejecutará en Artisan.
     *
     * @var string
     */
    protected $signature = 'empleados:update-passwords';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Actualizar contraseñas de empleados usando su cédula encriptada con bcrypt';

    /**
     * Ejecutar el comando.
     */
       public function handle()
        {
             DB::table('empleados')
                ->where('periodo', 2024)
                ->orderBy('registro') // Asegura el orden para evitar registros repetidos
                ->chunk(1000, function ($empleados) {
                    foreach ($empleados as $empleado) {
                        DB::table('empleados')
                            ->where('registro', $empleado->registro)
                            ->update(['contrasena' => Hash::make($empleado->cedula)]);
                    }
                });
        
            $this->info('Contraseñas actualizadas correctamente.');
        }
}
