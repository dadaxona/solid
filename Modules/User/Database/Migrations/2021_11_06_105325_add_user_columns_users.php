<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserColumnsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('education_degree', ['high', 'middle', 'low'])->default('low');
            $table->enum('marriage', ['yes', 'no'])->default('no');
            $table->renameColumn('address', 'address_registred');
            $table->string('address_living')->nullable();
            $table->string('estimated_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['education_degree', 'marriage', 'address_living', 'estimated_address']);
            $table->renameColumn('address_registred', 'address');
        });
    }
}