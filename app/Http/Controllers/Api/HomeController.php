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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class HomeController extends Controller
{
    //
    public function DashbordBuyer(Request $request)
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
        })->sum('item_price');

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

    public function DashbordSeller(Request $request)
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

        $totelCategorys = Category::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->count();

        $TotelCountOrder = OrderProduct::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->sum('item_price');

        $totelCategorys = Category::whereHas('store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->store_id);
        })->count();

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
                'مجموع المنتجات' => $totelCategorys,
                'مجموع اسعار المبيعات' => "$TotelCountOrder$",
                'عدد مدراء المتجر الخاص بك ' => $totelSellers,

            ],
        ]);
    }


    
}
