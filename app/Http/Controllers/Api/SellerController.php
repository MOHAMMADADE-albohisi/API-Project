<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    //

    public function OrderProducts(Request $request)
    {
        $orderproducts = OrderProduct::where('store_id', '=', $request->user()->store_id)->whereHas('order', function ($query) use ($request) {
            $query->where('status', '=', 'Waiting');
        })->get();
        $orderproducts->load('order');
        $orderproducts->load('product');
        $orderproducts->load('buyer');
        $orderproducts->load('store');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orderproducts,
        ]);
    }
    
}
