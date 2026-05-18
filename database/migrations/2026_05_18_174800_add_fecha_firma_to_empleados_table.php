<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaFirmaToEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->timestamp('fecha_firma')->nullable()->after('firma');
        });
    }

    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('fecha_firma');
        });
    }
}
