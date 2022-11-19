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


            $table->foreignId('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->foreignId('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');

            $table->foreignId('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('count')->unsigned();
            $table->float('item_price');
            $table->float('total');
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
