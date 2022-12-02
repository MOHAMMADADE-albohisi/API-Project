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
        $orderDriver->load('driver');
        $orderDriver->load('buyer');

        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orderDriver,
        ]);
    }

    public function store(Request $request, OrderDriver $orderdriver)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|numeric|exists:order_products,id',
            'driver_id' => 'required|numeric|exists:drivers,id',
            'buyer_id' => 'required|numeric|exists:buyers,id',
            'store_id' => 'required|numeric|exists:stores,id',
            'count' => 'required|numeric',
            'item_price' => 'required|numeric',
        ]);
        if (!$validator->fails()) {
            $orderdriver = new OrderDriver();
            $orderdriver->count = $request->input('count');
            $orderdriver->item_price = $request->input('item_price');
            $orderdriver->order_id = $request->input('order_id');
            $orderdriver->buyer_id = $request->input('buyer_id');
            $orderdriver->store_id = $request->input('store_id');
            $seller = Auth::guard('seller')->user();
            $orderdriver->seller_id = $seller->id;
            $orderdriver->driver_id = $request->input('driver_id');
            $isSaved = $orderdriver->save();
            if ($isSaved) {
                $order = Order::where('id', $request->get('order_id'))->first();
                $order->status = 'Processing';
                $order->save();
            }

            return response()->json(
                [

                    'message' => $isSaved ? 'تم ارسال الطلب الى السائق' : 'لم يتم ارسال الطلب'
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
