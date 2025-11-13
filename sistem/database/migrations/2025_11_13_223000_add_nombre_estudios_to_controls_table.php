<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNombreEstudiosToControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('controls', function (Blueprint $table) {
            if (!Schema::hasColumn('controls', 'nombre')) {
                $table->string('nombre')->nullable()->after('id');
            }
            if (!Schema::hasColumn('controls', 'estudios')) {
                $table->string('estudios')->nullable()->after('nombre');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('controls', function (Blueprint $table) {
            if (Schema::hasColumn('controls', 'estudios')) {
                $table->dropColumn('estudios');
            }
            if (Schema::hasColumn('controls', 'nombre')) {
                $table->dropColumn('nombre');
            }
        });
    }
}
