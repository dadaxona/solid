<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('user_id');
            $table->string('name');
            $table->string('second_name');
            $table->boolean('is_bot')->default(false);
            $table->timestamps();
            $table->string('full_name')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('registration_status')->default('pending');
            $table->bigInteger('region_id')->nullable();
            $table->bigInteger('direction_id')->nullable();
            $table->boolean('is_admin')->default(false);
        });
        Schema::create('telegram_chat_telegram_user', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('telegram_chat_id');
            $table->bigInteger('telegram_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_users');
        Schema::dropIfExists('telegram_chat_telegram_user');
    }
}