<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllersService;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nafezly\Payments\Classes\PayPalPayment;
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
        // $orders->load('product');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orders,
        ]);
    }


    public function store(Request $request)
    {
        //
        $cart = json_decode($request->cart, true);
        $storeId = auth('buyer')->user()->store_id;
        $userId = auth('buyer')->id();
        $validator = Validator::make([
            'cart' => $cart,
            'payment_type' => $request->payment_type,
            'store_id' => 'required|numeric|exists:stores,id',
            'total' => 'required|numeric',
            'count' => 'required|numeric|min:1',
            'payment_status' => 'required|string|in:Paid,Waiting,cancel',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'cart' => 'array',
            'cart.*.product_id' => [
                'required',
                Rule::exists('products', 'id')->where(function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                })
            ],
            'cart.*.quantity' => 'required|integer|min:1',
            'payment_type' => 'required|string|in:Cash,Online',
        ]);

        if (!$validator->fails()) {
            $orderProducts = [];
            $overalTotal = 0;
            foreach ($cart as $orderProduct) {
                $product = Product::findOrFail($orderProduct['product_id']);
                $newOrderProduct = new OrderProduct();
                $newOrderProduct->product_id = $orderProduct['product_id'];
                $newOrderProduct->quantity = $orderProduct['quantity'];
                $newOrderProduct->total = $orderProduct['quantity'] * $product->price;
                $newOrderProduct->buyer_id = auth('buyer')->id();
                $newOrderProduct->store_id = $storeId;


                $overalTotal += $newOrderProduct->total;
                array_push($orderProducts, $newOrderProduct);
            }
            $order = new Order();
            $order->buyer_id = auth('buyer')->id();
            $order->payment_type = $request->get('payment_type');
            $order->longitude = $request->get('longitude');
            $order->latitude = $request->get('latitude');
            $order->total = $overalTotal;
            $order->store_id = $storeId;
            if ($request->input('payment_type') == 'Online') {
                $payPal = new PayPalPaymentController();
                return $payPal->sendPayment($request);
            }
            $isSaved = $order->save();
            if ($isSaved) {
                $savedOrderProducts = $order->orderProducts()->saveMany($orderProducts);
                foreach ($savedOrderProducts as $orderProduct) {
                    $product = $orderProduct->product;
                    $product->save();
                }
            }
            return response()->json(
                ['message' => $isSaved ? 'تم ارسال الطلب بنجاح' : 'فشل في ارسال الطلب'],
                $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
            );
        } else {
            //TODO Handle your error
            return response()->json(['message' => $validator->getMessageBag()->first()], 400);
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
