<?php

use App\Models\TelegramUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDfiMasternodesTable extends Migration
{
    public function up()
    {
        Schema::create('dfi_masternodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(TelegramUser::class, 'telegramUserId');
            $table->string('name')->nullable();
            $table->string('masternode_id')->nullable()->unique();
            $table->string('owner_address')->nullable()->unique();
            $table->string('operator_address')->nullable()->unique();
            $table->boolean('alarm_on')->default(false);
            $table->boolean('synced_masternode_monitor')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dfi_masternodes');
    }
}
