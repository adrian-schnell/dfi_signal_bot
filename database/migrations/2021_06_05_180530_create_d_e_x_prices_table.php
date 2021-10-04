<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDEXPricesTable extends Migration
{
    public function up()
    {
        Schema::create('dex_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dex_id')->unique();
            $table->string('name')->unique();
            $table->float('price_to_dfi', 20, 10)->default(0);
            $table->float('price_from_dfi', 20, 10)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dex_prices');
    }
}
