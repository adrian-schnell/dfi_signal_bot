<?php

use App\Models\UserMasternode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMintedBlocksTable extends Migration
{
	public function up()
	{
		Schema::create('minted_blocks', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignIdFor(UserMasternode::class)->constrained()->cascadeOnDelete();
			$table->string('spent_txid')->nullable();
			$table->integer('mintBlockHeight')->nullable();
			$table->integer('spentBlockHeight')->nullable();
			$table->string('mint_txid')->nullable();
			$table->float('value', 20, 12)->default(0);
			$table->string('address')->nullable();
			$table->string('block_hash')->nullable();
			$table->timestamp('block_time')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('minted_blocks');
	}
}
