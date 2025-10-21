<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveAutocreadoFromDiagnostics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reemplazar la subcadena " (autocreado)" en description si existe
        if (Schema::hasTable('diagnostics')) {
            // usar SQL simple para compatibilidad
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement("UPDATE `diagnostics` SET `description` = TRIM(REPLACE(`description`, ' (autocreado)', '')) WHERE `description` LIKE '%(autocreado)%'");
            } elseif ($driver === 'pgsql') {
                DB::statement("UPDATE diagnostics SET description = trim(replace(description, ' (autocreado)', '')) WHERE description LIKE '%(autocreado)%'");
            } else {
                // Fallback: usar query builder
                $rows = DB::table('diagnostics')->where('description', 'like', '%(autocreado)%')->get(['id', 'description']);
                foreach ($rows as $row) {
                    $new = trim(str_replace(' (autocreado)', '', $row->description));
                    DB::table('diagnostics')->where('id', $row->id)->update(['description' => $new]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revertible: no reaplicaremos '(autocreado)'
    }
}
