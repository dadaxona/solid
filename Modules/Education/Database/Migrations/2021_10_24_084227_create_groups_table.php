<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->timestamps();
            $table->string('name')->nullable();
            $table->bigInteger('room_id')->nullable();
            $table->bigInteger('course_id')->nullable();
        });
        Schema::create('group_student', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('group_id');
            $table->bigInteger('student_id');
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
        Schema::dropIfExists('groups');
        Schema::dropIfExists('group_student');
    }
}