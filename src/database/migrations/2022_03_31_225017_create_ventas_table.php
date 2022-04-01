<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comercio_id');
            //$table->foreign('comercio_id')->references('id')->on('comercios');
            $table->bigInteger('monto');
            $table->text('cod');
            $table->smallInteger('delete');
            $table->string('terminal_mac');
            $table->string('terminal_id');
            $table->integer('dispositivo_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
