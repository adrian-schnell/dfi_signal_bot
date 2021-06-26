<?php

use App\Models\Server;
use App\Models\TelegramUser;
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

        Schema::create('servers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(TelegramUser::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('alarm_on')->default(true);
            $table->timestamps();
        });

        Schema::create('server_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Server::class)->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropColumn('user_sync_key');
        });

        Schema::dropIfExists('servers');
        Schema::dropIfExists('servers_stats');
    }
}
