<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupMintedBlocksTable extends Migration
{
	public function up()
	{
		Schema::table('minted_blocks', function (Blueprint $table) {
			$table->removeColumn('spent_txid');
			$table->removeColumn('mint_txid');
		});
	}

	public function down()
	{
		Schema::table('minted_blocks', function (Blueprint $table) {
            $table->string('spent_txid')->nullable();
            $table->string('mint_txid')->nullable();
        });
	}
}
