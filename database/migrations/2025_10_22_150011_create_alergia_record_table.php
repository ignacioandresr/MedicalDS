<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlergiaRecordTable extends Migration
{
    public function up()
    {
        Schema::create('alergia_record', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alergia_id');
            $table->unsignedBigInteger('record_id');
            $table->foreign('alergia_id')->references('id')->on('alergias')->onDelete('cascade');
            $table->foreign('record_id')->references('id_historial')->on('records')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alergia_record');
    }
}
