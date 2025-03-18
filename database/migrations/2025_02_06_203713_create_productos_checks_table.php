<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('codigo')->unique();
            $table->bigInteger('productcat_id');
            $table->bigInteger('cliente_id');
            $table->timestamps();

            $table->foreign('productcat_id')->references('id')->on('producto_categorias')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_checks');
    }
}
