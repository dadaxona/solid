<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixColumnsInDeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deals', function (Blueprint $table){
            $table->dropColumn(['budjet', 'title']);
            $table->string('code')->default('');
            $table->integer('payment_term')->default(0);
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->text('created_by_conclusion')->nullable();

            $table->unsignedBigInteger('monitored_by')->nullable();
            $table->foreign('monitored_by')->references('id')->on('users');
            $table->timestamp('monitored_at', 0)->nullable();

            $table->unsignedBigInteger('committee_member')->nullable();
            $table->foreign('committee_member')->references('id')->on('users');
            $table->text('committee_conclusion')->nullable();
            $table->timestamp('committee_date', 0)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deals', function (Blueprint $table){
            $table->dropColumn([
                'code', 'payment_term', 'created_by', 'created_by_conclusion', 'monitored_by', 'monitored_at', 'committee_member', 'committee_conclusion', 'committee_date'
            ]);
        });
    }
}