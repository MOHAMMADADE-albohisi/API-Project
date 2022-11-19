<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetalisController extends Controller
{
    //

    public function ProductDetails($id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $product,
        ]);
    }
}
