<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ActualizarContrasenas extends Command
{
    protected $signature = 'empleados:update-passwords {registros*}';
    protected $description = 'Actualiza las contraseñas de empleados específicos usando un WHERE IN';

    public function handle()
    {
        $registros = $this->argument('registros'); // Lista de registros pasados como argumento

        if (empty($registros)) {
            $this->error('Debes proporcionar al menos un registro.');
            return;
        }

        // Obtiene las cédulas de los empleados filtrados
        $empleados = DB::table('empleados')
            ->whereIn('cedula', $registros)
            ->pluck('cedula', 'registro'); // Devuelve un array [registro => cedula]

        if ($empleados->isEmpty()) {
            $this->info('No se encontraron empleados con los registros proporcionados.');
            return;
        }

        // Prepara los datos de actualización
        $updates = [];
        foreach ($empleados as $registro => $cedula) {
            $updates[$registro] = Hash::make($cedula);
        }

        // Actualiza las contraseñas en la base de datos
        foreach ($updates as $registro => $hashedPassword) {
            DB::table('empleados')
                ->where('registro', $registro)
                ->update(['contrasena' => $hashedPassword]);
        }

        $this->info('Contraseñas actualizadas correctamente para los registros: ' . implode(', ', $registros));
    }
}
