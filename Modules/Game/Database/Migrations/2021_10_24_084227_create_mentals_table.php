<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentals', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->jsonb('numbers');
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->jsonb('answers')->default('{}');
            $table->jsonb('result')->default('{}');
            $table->integer('limit')->default(0);
            $table->integer('room')->default(0);
            $table->integer('number_of_tasks')->default(0);
            $table->enum('type', ['game', 'file'])->default('file');
            $table->float('number_delay')->default(0);
            $table->float('interval')->default(0);
            $table->bigInteger('lesson_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mentals');
    }
}
