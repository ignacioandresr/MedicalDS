<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
 {
 Schema::create('diagnostics', function (Blueprint $table) {
 $table->id();
 $table->string('description');
 $table->date('date');
 $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('diagnostics');
    }
}
