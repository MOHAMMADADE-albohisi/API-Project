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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->foreignId('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            $table->foreignId('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');
            
            $table->foreignId('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->foreignId('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');

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
        Schema::dropIfExists('sales');
    }
};
