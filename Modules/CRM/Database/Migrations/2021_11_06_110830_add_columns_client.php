<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('spouse')->nullable();
            $table->string('spouse_work')->nullable();
            $table->integer('children_count')->default(0);
            $table->integer('family_member_count')->default(0);
            $table->integer('main_family_expense')->default(0);
            $table->string('home_type')->nullable();
            $table->enum('home_owning', ['yes', 'no'])->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['spouse', 'spouse_work', 'children_count', 'family_member_count', 'main_family_expense', 'home_type', 'home_owning']);
        });
    }
}