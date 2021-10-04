<?php

use App\Models\MintedBlock;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MintedBlockReported extends Migration
{
	public function up()
	{
		Schema::table('minted_blocks', function (Blueprint $table) {
			$table->boolean('is_reported')->default(false)->after('block_time');
		});
		$blocks = MintedBlock::all();
		$blocks->each(function(MintedBlock $block) {
		   $block->update([
		     'is_reported' => true,
           ]);
        });
	}

	public function down()
	{
		Schema::table('minted_blocks', function (Blueprint $table) {
            $table->dropColumn('is_reported');
        });
	}
}
