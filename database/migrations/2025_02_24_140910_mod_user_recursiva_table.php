<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModUserRecursivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function(Blueprint $table){
            $table->id();
            $table->bigInteger('codigo');
            $table->string('full_name');
            $table->string('cargo');
            $table->integer('telefono');
            $table->string('Area');
            $table->bigInteger('jefe')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('jefe')->references('id')->on('empleados')->onDelete('cascade');

        });
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
