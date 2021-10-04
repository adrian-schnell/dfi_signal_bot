<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveFlagToUserMasternodes extends Migration
{
	public function up()
	{
		Schema::table('user_masternodes', function (Blueprint $table) {
			$table->boolean('is_active')->default(true)->after('synced_masternode_monitor');
		});
	}

	public function down()
	{
		Schema::table('user_masternodes', function (Blueprint $table) {
			$table->dropColumn('is_active');
		});
	}
}
