<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    //


    public function index(Request $request)
    {
        // $categorie = Category::with('Product')->get();
        $categorie = Category::withCount(['Product', 'Store'])->whereHas('Store', function ($query) use ($request) {
            $query->where('store_id', '=', $request->user()->id);
        })->get();

        return response()->json([
            'stuts' => true,
            'message' => 'Success',
            'data' => $categorie,
        ]);
    }
}
