<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('symptoms', function (Blueprint $table) {
            if (! Schema::hasColumn('symptoms', 'patient_id')) {
                $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('symptoms', function (Blueprint $table) {
            if (Schema::hasColumn('symptoms', 'patient_id')) {
                $table->dropForeign(['patient_id']);
                $table->dropColumn('patient_id');
            }
        });
    }
};
