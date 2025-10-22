<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnfermedadRecordTable extends Migration
{
    public function up()
    {
        Schema::create('enfermedad_record', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enfermedad_id');
            $table->unsignedBigInteger('record_id');
            $table->foreign('enfermedad_id')->references('id')->on('enfermedades')->onDelete('cascade');
            $table->foreign('record_id')->references('id_historial')->on('records')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enfermedad_record');
    }
}
