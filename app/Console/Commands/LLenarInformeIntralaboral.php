<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InformesIntralaboralesController;

class LLenarInformeIntralaboral extends Command
{
    protected $signature = 'informe:update-intra {registros*}';
    protected $description = 'Actualiza o crea un reporte intralaboral';

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

        // Prepara los datos de actualización
        foreach ($empleados as $empleado) {
            $fichadato = DB::table('fichadatos')
            ->where('registro', $empleado->registro)->first();
            if(empty($fichadato)){
                continue;
            }else{
                if($empleado->nivelSeguridad== config('constants.TIPO_A')){
                    InformesIntralaboralesController::generarInformeIntraA($fichadato);
                }
                if($empleado->nivelSeguridad  == config('constants.TIPO_B')){
                    InformesIntralaboralesController::generarInformeIntraB($fichadato);
                }
            }
        }
        $this->info('Informes intralaborales actualizadas correctamente para los registros: ' . implode(', ', $registros));
    }
}
