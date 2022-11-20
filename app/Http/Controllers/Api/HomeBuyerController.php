<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeBuyerController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $products
        ]);
    }
}
