<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexMemoriesTable extends Migration
{
    public function up()
    {
        Schema::create('index_memories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->unique();
            $table->unsignedBigInteger('value')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('index_memories');
    }
}
