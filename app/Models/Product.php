<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    public function Store()
    {
        return $this->hasMany(Store::class, 'store_id', 'id');
    }

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'product_id', 'id');
    }


    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }

    protected $hidden = [
        'status',
        'updated_at',
        'created_at',
        'image'
    ];
}
