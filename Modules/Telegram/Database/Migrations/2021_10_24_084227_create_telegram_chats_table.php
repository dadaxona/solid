<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_chats', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('chat_id');
            $table->string('state');
            $table->timestamps();
            $table->string('language')->default('uz_lat');
            $table->bigInteger('job_application_id')->nullable();
            $table->bigInteger('telegram_bot_id')->nullable();
            $table->boolean('is_admin_chat')->default(false);
            $table->timestamp('last_published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_chats');
    }
}
