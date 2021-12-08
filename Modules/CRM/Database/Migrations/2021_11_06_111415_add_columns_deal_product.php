<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsDealProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deal_product', function (Blueprint $table) {
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('paid_price')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deal_product', function (Blueprint $table) {
            $table->dropColumn([
                'quantity', 'price', 'paid_price', 'discount'
            ]);
        });
    }
}