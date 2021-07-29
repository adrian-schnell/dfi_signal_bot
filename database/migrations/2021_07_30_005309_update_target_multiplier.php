<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTargetMultiplier extends Migration
{
	public function up()
	{
		Schema::table('masternodes', function (Blueprint $table) {
			$table->json('target_multipliers')
                ->after('target_multiplier')
                ->default(json_encode([]))
                ->nullable();
			$table->string('timelock')->nullable()->after('target_multipliers');
		});
	}

	public function down()
	{
		Schema::table('masternodes', function (Blueprint $table) {
			$table->dropColumn('target_multipliers');
			$table->dropColumn('timelock');
		});
	}
}
