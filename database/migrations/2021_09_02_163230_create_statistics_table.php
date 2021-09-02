<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
	public function up()
	{
		Schema::create('statistics', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->date('date')->index();
            $table->integer('user_count')->default(0);
            $table->integer('messages_sent')->default(0);
            $table->integer('commands_received')->default(0);
            $table->integer('minted_blocks')->default(0);
		});
	}

	public function down()
	{
		Schema::dropIfExists('statistics');
	}
}
