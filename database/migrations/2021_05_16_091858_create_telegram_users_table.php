<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUsersTable extends Migration
{
    public function up()
    {
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('telegramId')->unique();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('username')->nullable();
            $table->string('language', 2)->default('de');
            $table->string('status', 36)->default('member');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('telegram_users');
    }
}
