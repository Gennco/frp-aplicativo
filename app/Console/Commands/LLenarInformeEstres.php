<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\InformesEstresController;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;


class LLenarInformeEstres extends Command
{
    protected $signature = 'informe:update-estres {registros*}';
    protected $description = 'Actualiza o crea un reporte estres';

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
                Auth::setUser($empleado);
                InformesEstresController::generarInformeEstres(Auth::user(),$fichadato);
            }
        }
        $this->info('Informes estres actualizados correctamente para los registros: ' . implode(', ', $registros));
    }

    function setFakeUser($empleado){

    }
}
