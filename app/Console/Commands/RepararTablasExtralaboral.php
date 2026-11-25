<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class RepararTablasExtralaboral extends Command
{
    protected $signature = 'extralaboral:reparar-tablas
                            {--apply : Aplica los movimientos; sin esta opcion solo muestra una vista previa}
                            {--registro= : Limita la reparacion a un registro especifico}';

    protected $description = 'Mueve las respuestas extralaborales a ext1 o ext1a segun el tipo de empleado';

    public function handle()
    {
        $tables = ['ext1', 'ext1a'];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                $this->error("No existe la tabla {$table}.");
                return 1;
            }
        }

        $columnsByTable = [];
        foreach ($tables as $table) {
            $columnsByTable[$table] = Schema::getColumnListing($table);
            sort($columnsByTable[$table]);
        }

        if ($columnsByTable['ext1'] !== $columnsByTable['ext1a']) {
            $this->error('Las tablas ext1 y ext1a no tienen las mismas columnas; no se modifico ningun dato.');
            return 1;
        }

        if (!in_array('registro', $columnsByTable['ext1'], true)) {
            $this->error('Las tablas deben tener la columna registro.');
            return 1;
        }

        $apply = $this->option('apply');
        if ($apply && !$this->confirm('Se moveran respuestas entre ext1 y ext1a. ¿Deseas continuar?')) {
            $this->info('Operacion cancelada; no se modifico ningun dato.');
            return 0;
        }

        $this->info($apply ? 'Modo aplicacion.' : 'Vista previa: no se modificara ningun dato.');
        $totals = ['moved' => 0, 'duplicates' => 0, 'conflicts' => 0, 'unclassified' => 0];

        $this->repairDirection('ext1', 'ext1a', 'B', $columnsByTable['ext1'], $apply, $totals);
        $this->repairDirection('ext1a', 'ext1', 'A', $columnsByTable['ext1'], $apply, $totals);

        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Respuestas a mover/movidas', $totals['moved']],
                ['Duplicados identicos a quitar/eliminados', $totals['duplicates']],
                ['Conflictos preservados', $totals['conflicts']],
                ['Sin tipo de empleado verificable', $totals['unclassified']],
            ]
        );

        if (!$apply && ($totals['moved'] > 0 || $totals['duplicates'] > 0)) {
            $this->comment('Revisa el resultado y ejecuta con --apply para aplicar la reparacion.');
        }

        return $totals['conflicts'] > 0 || $totals['unclassified'] > 0 ? 1 : 0;
    }

    private function repairDirection($source, $target, $targetType, array $columns, $apply, array &$totals)
    {
        $query = DB::table($source)->orderBy('registro');
        if ($this->option('registro') !== null) {
            $query->where('registro', $this->option('registro'));
        }

        $query->chunkById(200, function ($rows) use ($source, $target, $targetType, $columns, $apply, &$totals) {
            $registrations = $rows->pluck('registro')->all();
            $employeeTypes = DB::table('empleados')
                ->whereIn('registro', $registrations)
                ->pluck('nivelSeguridad', 'registro');

            foreach ($rows as $row) {
                $employeeType = $employeeTypes->get($row->registro);
                if (!in_array($employeeType, ['A', 'B'], true)) {
                    $totals['unclassified']++;
                    continue;
                }

                if ($employeeType !== $targetType) {
                    continue;
                }

                $sourceData = [];
                foreach ($columns as $column) {
                    $sourceData[$column] = $row->{$column};
                }

                $existing = DB::table($target)
                    ->where('registro', $row->registro)
                    ->first();

                if ($existing) {
                    if (!$this->rowsMatch($sourceData, $existing, $columns)) {
                        $totals['conflicts']++;
                        $this->warn("Conflicto en registro {$row->registro}; se conservaron ambas filas.");
                        continue;
                    }

                    if ($apply) {
                        DB::transaction(function () use ($source, $row) {
                            $deleted = DB::table($source)->where('registro', $row->registro)->delete();
                            if ($deleted !== 1) {
                                throw new RuntimeException("No se pudo retirar el duplicado del registro {$row->registro}.");
                            }
                        });
                    }
                    $totals['duplicates']++;
                    continue;
                }

                if ($apply) {
                    DB::transaction(function () use ($source, $target, $row, $sourceData) {
                        DB::table($target)->insert($sourceData);
                        $deleted = DB::table($source)->where('registro', $row->registro)->delete();
                        if ($deleted !== 1) {
                            throw new RuntimeException("No se pudo retirar el origen del registro {$row->registro}.");
                        }
                    });
                }
                $totals['moved']++;
            }
        }, 'registro');
    }

    private function rowsMatch(array $sourceData, $targetRow, array $columns)
    {
        foreach ($columns as $column) {
            $sourceValue = $sourceData[$column];
            $targetValue = $targetRow->{$column};

            if ($sourceValue === null || $targetValue === null) {
                if ($sourceValue !== $targetValue) {
                    return false;
                }
                continue;
            }

            if ((string) $sourceValue !== (string) $targetValue) {
                return false;
            }
        }

        return true;
    }
}
