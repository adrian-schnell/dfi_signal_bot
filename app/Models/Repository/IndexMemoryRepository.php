<?php

namespace App\Models\Repository;

use App\Models\IndexMemory;
use App\Models\MintedBlock;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IndexMemoryRepository
{
	const LATEST_BLOCK_HEIGHT = 'latest_block_height';

	public function getLatestBlockIndex(): IndexMemory
	{
		try {
			$index = IndexMemory::where('type', self::LATEST_BLOCK_HEIGHT)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			$index = IndexMemory::create([
				'type'  => self::LATEST_BLOCK_HEIGHT,
				'value' => MintedBlock::orderByDesc('mintBlockHeight')->first()->mintBlockHeight ?? 0,
			]);
		}

		return $index;
	}

	public function blockDifferenceToBlock(int $block): int
	{
		return $block - $this->getLatestBlockIndex()->value;
	}

	public function incrementBlockHeightIndex(): void
	{
		$this->getLatestBlockIndex()->increment('value');
	}
}
