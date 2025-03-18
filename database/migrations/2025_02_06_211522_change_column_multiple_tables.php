<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::table('productos', function (Blueprint $table) {
        //     $table->bigInteger('codigo')->change();
        // });

        DB::statement('ALTER TABLE productos ALTER COLUMN codigo TYPE BIGINT USING codigo::bigint');
        DB::statement('ALTER TABLE proveedores ALTER COLUMN cedula TYPE INTEGER USING cedula::bigint');
        DB::statement('ALTER TABLE proveedores ALTER COLUMN telefono TYPE INTEGER USING telefono::bigint');
        DB::statement('ALTER TABLE empresas ALTER COLUMN telefono TYPE INTEGER USING telefono::bigint');
        DB::statement('ALTER TABLE clientes ALTER COLUMN telefono TYPE INTEGER USING telefono::bigint');
    

        // Schema::table('proveedores', function (Blueprint $table) {
        //     // $table->integer('cedula')->change();
        //     $table->integer('telefono')->change();
        // });

        // Schema::table('empresas', function (Blueprint $table) {
        //     $table->integer('telefono')->change();
        // });

        // Schema::table('clientes', function (Blueprint $table) {
        //     $table->integer('telefono')->change();
        // });  

        // no me dejo correr la iogracion como esta documenta por un problema con el tipo de dato de la columna codigo como con los demas :)
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
