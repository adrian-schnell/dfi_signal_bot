<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupServerSync extends Migration
{
	public function up()
	{
        Schema::table('server_stats', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
        });
        Schema::table('servers', function (Blueprint $table) {
            $table->dropForeign(['telegram_user_id']);
        });
        Schema::dropIfExists('server_stats');
        Schema::dropIfExists('servers');
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropColumn('user_sync_key');
            $table->string('server_health_api_key')->nullable()->after('mn_monitor_sync_key');
        });
	}
}
