<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    //
    public function index(Request $request)
    {

        $orders = OrderProduct::withCount(['buyer', 'order', 'product'])->whereHas('buyer', function ($query) use ($request) {
            $query->where('buyer_id', '=', $request->user()->id);
        })->get();
        $orders->load('order');
        $orders->load('product');
        $orders->load('store');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orders,
        ]);
    }

    public function store(Request $request, Order $order)
    {

        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'total' => 'required|numeric',
            'payment_type' => 'required|string|in:Cash,Online',
            'payment_status' => 'required|string|in:Paid,Waiting',
            'count' => 'required|numeric',
            'item_price' => 'required|numeric',
            'product' => 'required|numeric|exists:products,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',

        ]);
        if (!$validator->fails()) {
            $order = new Order();
            $order->total = $request->input('total');
            $order->payment_type = $request->input('payment_type');
            $order->latitude = $request->input('latitude');
            $order->longitude = $request->input('longitude');
            $isSaved = $order->save();
            if ($isSaved) {
                $orderProduct = new OrderProduct();
                $orderProduct->store_id = $request->input('store_id');
                $orderProduct->count = $request->input('count');
                $orderProduct->item_price = $request->input('item_price');
                $orderProduct->product_id = $request->input('product');
                $buyer = Auth::guard('buyer')->user();
                $orderProduct->buyer_id = $buyer->id;
                $orderProduct->order_id = $order->id;
                $orderProduct->save();
            }
            return response()->json(
                [

                    'message' => $isSaved ? 'تم أرسال الطلب بنجاح' : 'فشل إنشاء الطلب'
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


    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $order = OrderProduct::find($id);
        $deleted = $order->delete();
        return response()->json(
            [
                'message' => $deleted ? 'تم حدف الطلب بنجاح' : 'فشل حدف الطلب',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }
}
