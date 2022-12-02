<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();


            $table->integer('quantity')->unsigned();
            $table->double('total');


            $table->foreignId('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->foreignId('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');

            $table->foreignId('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

            $table->foreignId('product_id');
            $table->foreign('product_id')->references('id')->on('products');


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
        Schema::dropIfExists('order_products');
    }
};
