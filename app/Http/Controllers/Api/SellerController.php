<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    //

    public function OrderProducts(Request $request)
    {

        $orderproducts = OrderProduct::where('store_id', '=', $request->user()->store_id)->whereHas('order', function ($query) use ($request) {
            $query->where('status', '=', 'Waiting');
        })->get();
        // $orderproducts->load('order');
        // // $orderproducts->load('product');
        // // $orderproducts->load('buyer');
        // // $orderproducts->load('store');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orderproducts,
        ]);
    }


    public function Suggestion(Request $request, $id)
    {
        $suggestions = Suggestion::find($id);
        $suggestions = Suggestion::withCount(['buyer'])->whereHas('buyer', function ($query) use ($request) {
            $query->where('buyer_id', '=', $request->user()->id);
        })->get();

        return response()->json([
            'data' => $suggestions,
        ]);
    }
}
