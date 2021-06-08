<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TelegramUserLanguageNullable extends Migration
{
	public function up()
	{
		Schema::table('telegram_users', function (Blueprint $table) {
            $table->string('language', 2)->default('de')->nullable()->change();
            $table->string('status', 36)->default('member')->nullable()->change();
		});
	}

	public function down()
	{
		Schema::table('telegram_users', function (Blueprint $table) {
            $table->string('language', 2)->default('de')->change();
            $table->string('status', 36)->default('member')->change();
		});
	}
}
