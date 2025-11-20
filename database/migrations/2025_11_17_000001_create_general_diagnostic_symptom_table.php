<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralDiagnosticSymptomTable extends Migration
{
    public function up()
    {
        Schema::create('general_diagnostic_symptom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_diagnostic_id')->constrained('general_diagnostics')->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained('symptoms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_diagnostic_symptom');
    }
}
