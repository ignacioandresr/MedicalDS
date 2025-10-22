<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionsToClinicalCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinical_cases', function (Blueprint $table) {
            $table->text('options')->nullable()->after('solution');
            $table->text('options_ru')->nullable()->after('options');
            $table->text('options_es')->nullable()->after('options_ru');
            $table->integer('correct_index')->nullable()->after('options_es');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinical_cases', function (Blueprint $table) {
            $table->dropColumn(['options', 'options_ru', 'options_es', 'correct_index']);
        });
    }
}
