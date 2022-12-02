<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function Seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }


    public function Buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }


    public function OrderDriver()
    {
        return $this->belongsTo(OrderDriver::class, 'order_id', 'id');
    }

    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function Driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }



    protected $hidden = [
        'status',
        'updated_at',
        'created_at'
    ];
}
