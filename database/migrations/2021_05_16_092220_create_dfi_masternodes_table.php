<?php

use App\Models\Masternode;
use App\Models\TelegramUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDfiMasternodesTable extends Migration
{
    public function up()
    {
        Schema::create('user_masternodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(TelegramUser::class, 'telegramUserId')->constrained('telegram_users');
            $table->string('name')->nullable();
            $table->foreignIdFor(Masternode::class)->constrained('masternodes');
            $table->boolean('alarm_on')->default(false);
            $table->boolean('synced_masternode_monitor')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_masternodes');
    }
}
