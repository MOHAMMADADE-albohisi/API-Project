<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderDriver;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class HomeController extends Controller
{
    //
    public function HomeBuyer(Request $request)
    {
        $totelOrdersWaiting = OrderProduct::withCount(['buyer', 'order', 'product'])->whereHas('order', function ($query) use ($request) {
            $query->where('status', '=', 'Waiting');
        })->count();

        $totelOrdersProcessing = OrderProduct::withCount(['buyer', 'order', 'product'])->whereHas('order', function ($query) use ($request) {
            $query->where('status', '=', 'Processing');
        })->count();

        $totelOrdersDelivered = OrderProduct::withCount(['buyer', 'order', 'product'])->whereHas('order', function ($query) use ($request) {
            $query->where('status', '=', 'Delivered');
        })->count();


        $categorys = Category::with('Product')->whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->get();


        $TotelCountOrder = OrderProduct::whereHas('store', function ($query) use ($request) {
            $query->where('buyer_id', '=', $request->user()->id);
        })->sum('total');

        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => [
                'عدد الطلبات في حالة الانتظار' => $totelOrdersWaiting,
                'عدد الطلبات في حالة المتابعة' => $totelOrdersProcessing,
                'عدد الطلبات المستلمة' => $totelOrdersDelivered,
                'مجموع حساب الطلبات' => "$TotelCountOrder$",
                'categorys' => $categorys,
            ]
        ]);
    }

    public function serchApi(Request $request, $name)
    {
        $products = Product::where("name", "like", "%" . $name . "%")->whereHas('Category', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->get();
        if ($products !== null) {
            return response()->json([
                'status' => true,
                'message' => "Success",
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Fail",
                'data' => 'نأسف  لايوجد المنتج الذي تبحث عنه عنه!',
            ]);
        }
    }

    public function HomeSeller(Request $request)
    {
        $totelOrders = OrderProduct::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->count();

        $totelBuyers = Buyer::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->count();

        $totelDrivers = Driver::whereHas('stroe', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->count();



        $TotelCountOrder = OrderProduct::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->sum('total');



        $totelSellers = Seller::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->count();

        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => [
                'محموع الطلبات' => $totelOrders,
                'مجموع الزبائن' => $totelBuyers,
                'مجموع السائقين ' => $totelDrivers,
                'مجموع اسعار المبيعات' => "$TotelCountOrder$",
                'عدد مدراء المتجر الخاص بك ' => $totelSellers,

            ],
        ]);
    }

    public function HomeDriver(Request $request)
    {


        $ToterOrders =  OrderDriver::whereHas('driver', function ($query) use ($request) {
            $query->where('driver_id', '=', $request->user()->id);
        })->count();


        $orderNew =  OrderDriver::whereDate('created_at',  '=',  Carbon::today())
            ->whereHas('driver', function ($query) use ($request) {
                $query->where('driver_id', '=', $request->user()->id);
            })->count();


        $TotelSaleOrder =  Sale::whereHas('OrderDriver', function ($query) use ($request) {
            $query->where('driver_id', '=', $request->user()->id);
        })->count();

        $TotelSaleOrderNew =  Sale::whereDate('created_at',  '=',  Carbon::today())
            ->whereHas('OrderDriver', function ($query) use ($request) {
                $query->where('driver_id', '=', $request->user()->id);
            })->count();


        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => [
                'جميع الطلبات' => $ToterOrders,
                'الطلبات اليومية' => $orderNew,
                'مجموع المبيعات ' => $TotelSaleOrder,
                'مجموع المبيعات اليويمة ' => $TotelSaleOrderNew,
            ]
        ]);
    }
}
