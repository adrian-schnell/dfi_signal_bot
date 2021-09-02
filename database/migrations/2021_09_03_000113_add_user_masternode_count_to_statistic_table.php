<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserMasternodeCountToStatisticTable extends Migration
{
	public function up()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->integer('masternode_count')->default(0)->after('user_count');
		});
	}

	public function down()
	{
		Schema::table('statistics', function (Blueprint $table) {
			$table->dropColumn('masternode_count');
		});
	}
}
