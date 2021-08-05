<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTargetMultiplierColumnMasternodeTable extends Migration
{
	public function up()
	{
		Schema::table('masternodes', function (Blueprint $table) {
			$table->dropColumn('target_multiplier');
		});
	}

	public function down()
	{
		Schema::table('masternodes', function (Blueprint $table) {
            $table->unsignedInteger('target_multiplier')->default(0)->nullable()->after('state');
        });
	}
}
