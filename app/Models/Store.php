<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    public function seller()
    {
        return $this->hasMany(Seller::class, 'store_id', 'id');
    }

    public function buyer()
    {
        return $this->hasMany(Buyer::class, 'store_id', 'id');
    }

    public function driver()
    {
        return $this->hasMany(Driver::class, 'store_id', 'id');
    }


    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'store_id', 'id');
    }

    public function OrderDriver()
    {
        return $this->hasMany(OrderDriver::class, 'store_id', 'id');
    }

    protected $hidden = [
        'updated_at',
    ];
}
