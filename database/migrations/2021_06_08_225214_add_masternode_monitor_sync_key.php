<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMasternodeMonitorSyncKey extends Migration
{
	public function up()
	{
		Schema::table('telegram_users', function (Blueprint $table) {
			$table->string('mn_monitor_sync_key')->nullable()->after('status');
		});
	}

	public function down()
	{
		Schema::table('telegram_users', function (Blueprint $table) {
			$table->dropColumn('mn_monitor_sync_key');
		});
	}
}
