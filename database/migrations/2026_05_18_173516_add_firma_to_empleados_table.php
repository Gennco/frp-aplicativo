<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmaToEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->longText('firma')->nullable()->after('control_periodo');
        });
    }

    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('firma');
        });
    }
}
