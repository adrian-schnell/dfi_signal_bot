<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnetimeNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('onetime_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('notifiable_type')->index();
            $table->unsignedBigInteger('notifiable_id')->index();
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('onetime_notifications');
    }
}
