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

    public function serchApi($name)
    {
        $products = Product::where("name", "like", "%" . $name . "%")->get();
        if ($products !== null) {
            return response()->json([
                'status' => true,
                'message' => "Success",
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "[ail",
                'data' => 'نأسف  لا يوجد المنتج الذي تبحث عنه!',
            ]);
        }
    }
}
