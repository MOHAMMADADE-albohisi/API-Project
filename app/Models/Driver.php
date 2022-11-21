<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public function stroe()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function orderDriver()
    {
        return $this->hasMany(OrderDriver::class, 'driver_id', 'id');
    }

    protected $hidden = [
        'password',
        'updated_at',
    ];
}
