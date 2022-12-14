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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->float('total');
            $table->enum('payment_type', ['Cash', 'Online']);
            $table->enum('payment_status', ['Paid', 'Waiting', 'cancel'])->default('Waiting');
            $table->enum('status', ['Waiting', 'Processing', 'Delivered', 'Canceled', 'Rejected'])->default('Waiting');
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->foreignId('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');
            $table->foreignId('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

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
        Schema::dropIfExists('orders');
    }
};
