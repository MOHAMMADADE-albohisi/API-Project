<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeBuyerController extends Controller
{
    //
    public function index(Request $request)
    {
        $products = Product::where('status', '=', 'Visible')->with('Category')->whereHas('Category', function ($query) use ($request) {
            $query->where('categorie_id', '=', $request->user()->id);
        })->get();
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $products
        ]);
    }

    public function serchApi(Request $request, $name)
    {
        $products = Product::where('status', '=', 'Visible')->where("name", "like", "%" . $name . "%")
            ->with('Category')->whereHas('Category', function ($query) use ($request) {
                $query->where('categorie_id', '=', $request->user()->id);
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
                'message' => "[ail",
                'data' => 'نأسف  لا يوجد المنتج الذي تبحث عنه!',
            ]);
        }
    }
}
