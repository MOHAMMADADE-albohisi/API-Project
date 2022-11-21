<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDriver;
use Illuminate\Http\Request;

class DriverControllerAll extends Controller
{
    //

    public function order(Request $request)
    {
        $orderDriver = OrderDriver::withCount(['seller', 'orderProduct', 'driver'])
            ->whereHas('driver', function ($query) use ($request) {
                $query->where('driver_id', '=', $request->user()->id);
            })->get();
        $orderDriver->load('orderProduct');
        $orderDriver->load('seller');
        $orderDriver->load('driver');

        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orderDriver,
        ]);
    }



    public function OrderDriver(Request $request, $id)
    {
        $orderdriver = OrderDriver::find($id);
        $orderdriver = OrderDriver::withCount(['seller', 'orderProduct', 'driver'])
            ->whereHas('driver', function ($query) use ($request) {
                $query->where('driver_id', '=', $request->user()->id);
            })->get();
        $orderdriver->load('orderProduct');
        $orderdriver->load('driver');
        $orderdriver->load('product');
        $orderdriver->load('buyer');
        $orderdriver->load('store');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orderdriver,
        ]);
    }
}
