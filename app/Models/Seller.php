<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class Seller extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function orderDriver()
    {
        return $this->hasMany(OrderDriver::class, 'seller_id', 'id');
    }

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'seller_id', 'id');
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }

    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
        'email_verified_at',
        'verificcation_code',
        'image',
    ];
}
