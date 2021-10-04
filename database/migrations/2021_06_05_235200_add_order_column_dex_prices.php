<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderColumnDexPrices extends Migration
{
	public function up()
	{
		Schema::table('dex_prices', function (Blueprint $table) {
			$table->integer('order')->default(0)->after('price_from_dfi');
		});
	}

	public function down()
	{
		Schema::table('dex_prices', function (Blueprint $table) {
			$table->dropColumn('order');
		});
	}
}
