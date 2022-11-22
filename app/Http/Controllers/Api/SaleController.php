<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    //

    public function index(Request $request)
    {
        $sales = Sale::whereHas('Seller', function ($query) use ($request) {
            $query->where('seller_id', '=', $request->user()->id);
        })->get();
        $sales->load('Product');
        $sales->load('Seller');
        $sales->load('Buyer');
        $sales->load('Order');
        $sales->load('OrderDriver');
        return response()->json([
            'Status' => true,
            'message' => "Success",
            'date' => $sales,
        ]);
    }


    public function Store(Request $request, Sale $sales)
    {

        $validator = validator($request->all(), [
            'product_id' => 'required|numeric|exists:products,id',
            'seller_id' => 'required|numeric|exists:sellers,id',
            'buyer_id' => 'required|numeric|exists:buyers,id',
            'order_id' => 'required|numeric|exists:orders,id',
        ]);
        if (!$validator->fails()) {
            $sales = new Sale();
            $sales->product_id = $request->input('product_id');
            $sales->seller_id = $request->input('seller_id');
            $sales->buyer_id = $request->input('buyer_id');
            $sales->order_id = $request->input('order_id');
            $driver = Auth::guard('driver')->user();
            $sales->driver_id = $driver->id;
            $isSaved = $sales->save();
            if ($isSaved) {
                $order = Order::where('id', $request->get('order_id'))->first();
                $order->status = 'Delivered';
                $order->save();
            }
            return response()->json(
                [

                    'message' => $isSaved ? 'تمت عملية الشراء بنجاح' : 'فشلت عملية الشراء'
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
