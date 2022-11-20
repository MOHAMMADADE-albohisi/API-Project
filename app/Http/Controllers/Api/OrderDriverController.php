<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDriver;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderDriverController extends Controller
{
    //

    public function index(Request $request)
    {
        $orderDriver = OrderDriver::withCount(['seller', 'orderProduct', 'driver'])
            ->whereHas('seller', function ($query) use ($request) {
                $query->where('seller_id', '=', $request->user()->id);
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

    public function store(Request $request, OrderDriver $orderdriver)
    {
        $validator = validator($request->all(), [
            'order_product_id' => 'required|numeric|exists:order_products,id',
            'driver_id' => 'required|numeric|exists:drivers,id',
        ]);
        if (!$validator->fails()) {
            $orderdriver = new OrderDriver();
            $orderdriver->order_product_id = $request->input('order_product_id');
            $seller = Auth::guard('seller')->user();
            $orderdriver->seller_id = $seller->id;
            $orderdriver->driver_id = $request->input('driver_id');
            $isSaved = $orderdriver->save();

            return response()->json(
                [

                    'message' => $isSaved ? 'Order created successfully' : 'Order Create failed'
                ],
                $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json(
                [
                    'message' => $validator->getMessageBag()->first()
                ],
                Response::HTTP_BAD_REQUEST

            );
        }
    }
}
