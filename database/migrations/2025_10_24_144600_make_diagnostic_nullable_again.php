<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeDiagnosticNullableAgain extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$driver = DB::getDriverName();

		if (! Schema::hasColumn('records', 'diagnostic_id')) {
			return;
		}

		if ($driver === 'mysql') {
			DB::statement('ALTER TABLE `records` DROP FOREIGN KEY `records_diagnostic_id_foreign`');
			DB::statement('ALTER TABLE `records` MODIFY `diagnostic_id` BIGINT UNSIGNED NULL');
			DB::statement('ALTER TABLE `records` ADD CONSTRAINT `records_diagnostic_id_foreign` FOREIGN KEY (`diagnostic_id`) REFERENCES `diagnostics` (`id`) ON DELETE CASCADE');
		} elseif ($driver === 'pgsql') {
			DB::statement('ALTER TABLE records DROP CONSTRAINT IF EXISTS records_diagnostic_id_foreign');
			DB::statement('ALTER TABLE records ALTER COLUMN diagnostic_id DROP NOT NULL');
			DB::statement('ALTER TABLE records ADD CONSTRAINT records_diagnostic_id_foreign FOREIGN KEY (diagnostic_id) REFERENCES diagnostics(id) ON DELETE CASCADE');
		} else {
			Schema::table('records', function (Blueprint $table) {
				$table->dropForeign(['diagnostic_id']);
				$table->unsignedBigInteger('diagnostic_id')->nullable()->change();
				$table->foreign('diagnostic_id')->references('id')->on('diagnostics')->onDelete('cascade');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$driver = DB::getDriverName();

		if (! Schema::hasColumn('records', 'diagnostic_id')) {
			return;
		}

		if ($driver === 'mysql') {
			DB::statement('ALTER TABLE `records` DROP FOREIGN KEY `records_diagnostic_id_foreign`');
			DB::statement('ALTER TABLE `records` MODIFY `diagnostic_id` BIGINT UNSIGNED NOT NULL');
			DB::statement('ALTER TABLE `records` ADD CONSTRAINT `records_diagnostic_id_foreign` FOREIGN KEY (`diagnostic_id`) REFERENCES `diagnostics` (`id`) ON DELETE CASCADE');
		} elseif ($driver === 'pgsql') {
			DB::statement('ALTER TABLE records DROP CONSTRAINT IF EXISTS records_diagnostic_id_foreign');
			DB::statement('ALTER TABLE records ALTER COLUMN diagnostic_id SET NOT NULL');
			DB::statement('ALTER TABLE records ADD CONSTRAINT records_diagnostic_id_foreign FOREIGN KEY (diagnostic_id) REFERENCES diagnostics(id) ON DELETE CASCADE');
		} else {
			Schema::table('records', function (Blueprint $table) {
				$table->dropForeign(['diagnostic_id']);
				$table->unsignedBigInteger('diagnostic_id')->nullable(false)->change();
				$table->foreign('diagnostic_id')->references('id')->on('diagnostics')->onDelete('cascade');
			});
		}
	}
}