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
        $categories = Category::where('status', '=', 'Visible')->get();


        return response()->json([
            'stuts' => true,
            'message' => 'Success',
            'data' => $categories,
        ]);
    }
}
