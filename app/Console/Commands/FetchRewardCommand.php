<?php

namespace App\Console\Commands;

use App\Http\Service\DefichainApiService;
use App\Models\MintedBlock;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class FetchRewardCommand extends Command
{
	protected $signature = 'update:rewards';
	protected $description = 'Reload the rewards if an error occured before';

	public function handle(DefichainApiService $defichainApi)
	{
		MintedBlock::where('value', -1)
			->chunk(50, function (Collection $blockCollection) use ($defichainApi) {
				$blockCollection->each(function (MintedBlock $block) use ($defichainApi) {
					$blockData = $defichainApi->getBlockData($block->mintBlockHeight);
					$block->update([
						'value' => (float) $blockData['reward'],
					]);
				});
			});
	}
}
