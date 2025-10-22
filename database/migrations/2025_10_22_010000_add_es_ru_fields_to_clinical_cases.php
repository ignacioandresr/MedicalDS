<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clinical_cases', function (Blueprint $table) {
            $table->string('title_es')->nullable()->after('title');
            $table->text('description_es')->nullable()->after('description');
            $table->text('steps_es')->nullable()->after('steps');
            $table->text('solution_es')->nullable()->after('solution');

            $table->string('title_ru')->nullable()->after('solution_es');
            $table->text('description_ru')->nullable()->after('title_ru');
            $table->text('steps_ru')->nullable()->after('description_ru');
            $table->text('solution_ru')->nullable()->after('steps_ru');
        });
    }

    public function down()
    {
        Schema::table('clinical_cases', function (Blueprint $table) {
            $table->dropColumn([
                'title_es','description_es','steps_es','solution_es',
                'title_ru','description_ru','steps_ru','solution_ru'
            ]);
        });
    }
};
