<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    public function stroe()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    protected $hidden = [
        'password',
        'updated_at',
    ];
}
