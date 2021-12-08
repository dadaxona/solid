<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bots', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('bot_name');
            $table->string('token');
            $table->string('status');
            $table->timestamps();
            $table->boolean('is_admin_bot')->default(false);
            $table->string('strategy')->nullable();
            $table->jsonb('settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_bots');
    }
}