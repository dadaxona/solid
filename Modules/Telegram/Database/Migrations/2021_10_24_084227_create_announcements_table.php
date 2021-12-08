<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->enum('type', ['sell', 'buy']);
            $table->string('state')->default('draft');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('price')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->bigInteger('telegram_chat_id')->nullable();
            $table->string('contact')->nullable();
            $table->string('name');
            $table->bigInteger('region_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
