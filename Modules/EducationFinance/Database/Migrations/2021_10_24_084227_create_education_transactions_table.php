<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_transactions', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('group_id');
            $table->bigInteger('student_id');
            $table->bigInteger('amount');
            $table->timestamp('performed_at');
            $table->timestamps();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education_transactions');
    }
}