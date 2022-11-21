<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    //


    public function index()
    {
        $categorie = Category::with('Product')->get();
        return response()->json([
            'data' => $categorie,
        ]);
    }
}
