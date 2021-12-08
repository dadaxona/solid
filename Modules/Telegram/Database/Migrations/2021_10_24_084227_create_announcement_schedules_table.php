<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement_schedules', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('telegram_bot_id');
            $table->bigInteger('announcement_id');
            $table->timestamp('planned_at');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->enum('status', ['draft', 'requested', 'ignored', 'waiting', 'published'])->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcement_schedules');
    }
}
