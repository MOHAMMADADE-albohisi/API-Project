<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function Product()
    {
        return $this->belongsToMany(Product::class, OrderProduct::class, 'order_id', 'product_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }



    public function orderDetails()
    {

        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function OrderDriver()
    {
        return $this->hasMany(OrderDriver::class, 'order_id', 'id');
    }

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'order_id', 'id');
    }







    protected $hidden = [
        'updated_at',
        'buyer_count',
    ];
}
