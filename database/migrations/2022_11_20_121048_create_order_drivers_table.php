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
        Schema::create('order_drivers', function (Blueprint $table) {
            $table->id();

            $table->integer('count')->unsigned();
            $table->float('item_price');

            $table->foreignId('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

            $table->foreignId('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->foreignId('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');

            $table->foreignId('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            $table->foreignId('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');

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
        Schema::dropIfExists('order_drivers');
    }
};
