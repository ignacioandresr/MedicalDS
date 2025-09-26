<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
 {
 Schema::create('patients', function (Blueprint $table) {
 $table->id();
 $table->string('rut')->unique();
 $table->string('name');
 $table->date('birth_date');
 $table->string('gender');
 $table->string('adress');
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
        Schema::dropIfExists('patients');
    }
}
