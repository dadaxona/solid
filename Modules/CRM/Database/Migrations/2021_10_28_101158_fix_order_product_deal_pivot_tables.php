<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixOrderProductDealPivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('order_product');
        Schema::create('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('quantity')->nullable();
            $table->bigInteger('price')->nullable();
            $table->timestamp('created_at', 0)->nullable();
        });

        Schema::dropIfExists('deal_product');
        Schema::create('deal_product', function (Blueprint $table) {
            $table->unsignedBigInteger('deal_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('deal_id')->references('id')->on('deals');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamp('created_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('quantity');
            $table->bigInteger('price');
            $table->timestamps();
        });
    }
}