<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InformesExtralaboralesController;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;


class LLenarInformeExtralaboral extends Command
{
    protected $signature = 'informe:update-extra {registros*}';
    protected $description = 'Actualiza o crea un reporte extralaborales';

    public function handle()
    {
        $registros = $this->argument('registros'); // Lista de registros pasados como argumento

        if (empty($registros)) {
            $this->error('Debes proporcionar al menos un registro.');
            return;
        }

        $empleados = DB::table('empleados')
            ->whereIn('registro', $registros)->get();
        if ($empleados->isEmpty()) {
            $this->info('No se encontraron empleados con los registros proporcionados.');
            return;
        }

        // Prepara los datos de actualizacion
        foreach ($empleados as $empleado) {
            $fichadato = DB::table('fichadatos')
            ->where('registro', $empleado->registro)->first();
            if(empty($fichadato)){
                continue;
            }else{
                InformesExtralaboralesController::generarInformeExtralaboral($empleado,$fichadato);
            }
        }
        $this->info('Informes estres actualizados correctamente para los registros: ' . implode(', ', $registros));
    }

}
