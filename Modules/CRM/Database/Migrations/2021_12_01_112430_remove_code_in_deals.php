<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCodeInDeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('status');
        });
        
        Schema::table('deals', function (Blueprint $table) {
            $table->enum('status', ['new', 'monitoring', 'monitoring_canceled', 'committee', 'committee_canceled', 'completed'])->default('new');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->string('code')->default('');
        });
    }
}