<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntermediaCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('intermedia_checks', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('producto_id');
        $table->unsignedBigInteger('producto_check_id');
        $table->timestamps();

        // Claves foráneas
        $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        $table->foreign('producto_check_id')->references('id')->on('productos_checks')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intermedia_check');
    }
}
