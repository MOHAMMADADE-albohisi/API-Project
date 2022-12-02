<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;



    public function Product()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    public function Store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }

    protected $hidden = [
        'product_count',
        'store_count',
        'created_at',
        'updated_at',
        'image',
    ];
}
