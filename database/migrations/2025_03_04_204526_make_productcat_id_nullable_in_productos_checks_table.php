<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeProductcatIdNullableInProductosChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('productcat_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('productos_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('productcat_id')->nullable(false)->change();
        });
    }
}
