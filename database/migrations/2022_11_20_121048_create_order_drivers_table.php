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
            $table->foreignId('order_product_id');
            $table->foreign('order_product_id')->references('id')->on('order_products');

            $table->foreignId('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

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
        Schema::dropIfExists('order_drivers');
    }
};
