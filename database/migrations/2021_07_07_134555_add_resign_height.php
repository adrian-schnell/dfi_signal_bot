<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResignHeight extends Migration
{
    public function up()
    {
        Schema::table('masternodes', function (Blueprint $table) {
            $table->integer('resign_height')->afer('creation_height')->default(-1);
            $table->integer('ban_height')->afer('resign_height')->default(-1);
        });
    }

    public function down()
    {
        Schema::table('masternodes', function (Blueprint $table) {
            $table->dropColumn('resign_height');
            $table->dropColumn('ban_height');
        });
    }
}
