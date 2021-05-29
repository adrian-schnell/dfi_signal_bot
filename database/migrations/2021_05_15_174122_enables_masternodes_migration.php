<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnablesMasternodesMigration extends Migration
{
	public function up()
	{
		Schema::create('masternodes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('masternode_id')->unique();
			$table->string('owner_address')->unique();
			$table->string('operator_address')->unique();
			$table->unsignedInteger('creation_height');
			$table->string('state');
			$table->unsignedInteger('minted_blocks')->default(0);
			$table->unsignedInteger('target_multiplier')->default(0)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
        Schema::dropIfExists('masternodes');
	}
}
