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

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dfi_masternodes');
    }
}
