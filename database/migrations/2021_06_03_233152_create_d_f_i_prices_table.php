<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDFIPricesTable extends Migration
{
    public function up()
    {
        Schema::create('dfi_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticker')->unique();
            $table->string('symbol')->nullable();
            $table->float('price', 15, 10);
            $table->float('volume_24h', 20, 10)->nullable();
            $table->float('volume_24h_change_24h', 5, 2)->nullable();
            $table->float('market_cap', 20, 10)->nullable();
            $table->float('market_cap_change_24h', 5, 2)->nullable();
            $table->float('percent_change_15m', 5, 2)->nullable();
            $table->float('percent_change_30m', 5, 2)->nullable();
            $table->float('percent_change_1h', 5, 2)->nullable();
            $table->float('percent_change_6h', 5, 2)->nullable();
            $table->float('percent_change_12h', 5, 2)->nullable();
            $table->float('percent_change_24h', 5, 2)->nullable();
            $table->float('percent_change_7d', 5, 2)->nullable();
            $table->float('percent_change_30d', 5, 2)->nullable();
            $table->float('percent_change_1y', 5, 2)->nullable();
            $table->float('ath_price')->default(0);
            $table->dateTime('ath_date');
            $table->float('percent_from_price_ath', 6, 3);

            $table->boolean('is_rounded')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dfi_prices');
    }
}
