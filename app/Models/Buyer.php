<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class Buyer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;



    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id', 'id');
    }

    public function Suggestions()
    {
        return $this->hasMany(Suggestion::class, 'buyer_id', 'id');
    }


    public function Complain()
    {
        return $this->hasMany(Complain::class, 'buyer_id', 'id');
    }


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }


    public function Sale()
    {
        return $this->hasMany(Sale::class, 'buyer_id', 'id');
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
        'verificcation_code',
        'email_verified_at',
        'image',
    ];
}
