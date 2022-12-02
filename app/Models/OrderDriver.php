<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDriver extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }

    public function orderDrivers()
    {
        return $this->hasMany(OrderDriver::class, 'order_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_id', 'id');
    }

    public function DriveOrder()
    {
        return $this->hasMany(OrderDriver::class, 'driver_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    protected $hidden = [
        'seller_count',
        'order_count',
        'updated_at',
        'created_at',
        'driver_count',
        'order_product_count',

    ];
}
