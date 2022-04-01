<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldosLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldos_logs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('comercio_id');
            $table->foreign('comercio_id')->references('id')->on('comercios');

            $table->unsignedBigInteger('ventas_id');
            $table->foreign('ventas_id')->references('id')->on('ventas');

            $table->string('accion');
            $table->bigInteger('saldo_act');
            $table->bigInteger('saldo_ant');

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
        Schema::dropIfExists('saldos_logs');
    }
}
