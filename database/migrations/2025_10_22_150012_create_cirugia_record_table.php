<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCirugiaRecordTable extends Migration
{
    public function up()
    {
        Schema::create('cirugia_record', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cirugia_id');
            $table->unsignedBigInteger('record_id');
            $table->foreign('cirugia_id')->references('id')->on('cirugias')->onDelete('cascade');
            $table->foreign('record_id')->references('id_historial')->on('records')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cirugia_record');
    }
}
