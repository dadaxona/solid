<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('room_id');
            $table->bigInteger('group_id');
            $table->timestamps();
            $table->boolean('monday')->nullable();
            $table->time('monday_from')->nullable();
            $table->time('monday_to')->nullable();
            $table->boolean('tuesday')->nullable();
            $table->time('tuesday_from')->nullable();
            $table->time('tuesday_to')->nullable();
            $table->boolean('wednesday')->nullable();
            $table->time('wednesday_from')->nullable();
            $table->time('wednesday_to')->nullable();
            $table->boolean('thursday')->nullable();
            $table->time('thursday_from')->nullable();
            $table->time('thursday_to')->nullable();
            $table->boolean('friday')->nullable();
            $table->time('friday_from')->nullable();
            $table->time('friday_to')->nullable();
            $table->boolean('saturday')->nullable();
            $table->time('saturday_from')->nullable();
            $table->time('saturday_to')->nullable();
            $table->boolean('sunday')->nullable();
            $table->time('sunday_from')->nullable();
            $table->time('sunday_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_schedules');
    }
}
