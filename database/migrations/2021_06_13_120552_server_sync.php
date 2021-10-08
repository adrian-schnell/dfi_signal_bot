<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServerSync extends Migration
{
    public function up()
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->uuid('user_sync_key')->after('status')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropColumn('user_sync_key');
        });
    }
}
