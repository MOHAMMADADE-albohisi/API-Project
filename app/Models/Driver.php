<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'driver_id', 'id');
    }
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'verificcation_code',
        'image',
    ];
}
