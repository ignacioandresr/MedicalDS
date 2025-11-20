<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralDiagnosticsTable extends Migration
{
    public function up()
    {
        Schema::create('general_diagnostics', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->date('date')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_diagnostics');
    }
}
