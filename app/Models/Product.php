<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;


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
