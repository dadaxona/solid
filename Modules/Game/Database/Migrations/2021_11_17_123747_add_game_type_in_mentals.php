<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameTypeInMentals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mentals', function (Blueprint $table) {
            $table->enum('game_type',['train', 'flash-card'])->default('train');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mentals', function (Blueprint $table) {
            $table->dropColumn('game_type');
        });
    }
}