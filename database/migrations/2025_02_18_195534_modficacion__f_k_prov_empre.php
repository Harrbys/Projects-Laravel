<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModficacionFKProvEmpre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {

            $table->dropForeign(['proveedor_id']);

            $table->dropColumn('proveedor_id');
        });

        Schema::table('proveedores', function (Blueprint $table) {

            $table->bigInteger('empresa_id')->unsigned()->nullable();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {

            $table->bigInteger('proveedor_id')->unsigned()->nullable();

            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');
        });
    }
}
