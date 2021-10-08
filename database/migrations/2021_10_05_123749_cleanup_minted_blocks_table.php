<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupMintedBlocksTable extends Migration
{
	public function up()
	{
		Schema::table('minted_blocks', function (Blueprint $table) {
			$table->dropColumn('spent_txid');
			$table->dropColumn('mint_txid');
			$table->dropColumn('spentBlockHeight');
            $table->unsignedBigInteger('user_masternode_id')->nullable()->change();

            $table->index('mintBlockHeight');
		});
	}

	public function down()
	{
		Schema::table('minted_blocks', function (Blueprint $table) {
            $table->string('spent_txid2021_06_13_120552_server_sync')->nullable();
            $table->string('mint_txid')->nullable();
            $table->integer('spentBlockeight')->nullable();
            $table->unsignedBigInteger('user_masternode_id')->change();

//            $table->dropIndex(['mintBlockHeight']);
        });
	}
}
