<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
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
            'list' => $products,
        ]);
    }

    public function Store(Request $request, Product $product)
    {
        $validator = validator($request->all(), [
            'categorie_id' => 'required|numeric|exists:categories,id',
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:5',
            'price' => 'required|numeric',
            'status' => 'required|string|in:Visible,InVisible',
            'image' => 'required|image|mimes:jpg,png',

        ]);
        if (!$validator->fails()) {
            $product = new Product();
            $product->categorie_id = $request->input('categorie_id');
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->status = $request->input('status');
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . str_replace(' ', '', $product->name) . '.' . $request->file('image')->extension();
                $request->file('image')->storePubliclyAs('product', $imageName, ['disk' => 'public']);
                $product->image = 'product/' . $imageName;
            }
            $isSaved = $product->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم اضافة منتج جديد الى المتجر الخاص بك' : 'فشل في اضافة المنتج'
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


    public function Update(Request $request, $id)
    {
        $product = Product::find($id);
        $validator = validator($request->all(), [
            'categorie_id' => 'required|numeric|exists:categories,id',
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:5',
            'price' => 'required|numeric',
            'status' => 'required|string|in:Visible,InVisible',
            'image' => 'nullable', '|image|mimes:jpg,png',
        ]);

        if (!$validator->fails()) {
            $product->categorie_id = $request->input('categorie_id');
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->status = $request->input('status');
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . str_replace(' ', '', $product->name) . '.' . $request->file('image')->extension();
                $request->file('image')->storePubliclyAs('product', $imageName, ['disk' => 'public']);
                $product->image = 'product/' . $imageName;
            }
            $isSaved = $product->save();
            return response()->json(
                ['message' => $isSaved ? ' تم تحديث المنتج بنجاح' : 'لم يتم تحديث المنتج'],
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


    public function destroy($id)
    {
        $product = Product::find($id);
        $deleted = $product->delete();
        return response()->json(
            [
                'message' => $deleted ? 'تم حدف المنتج الخاص بك' : 'فشلت عملية حدف المنتج!',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }



    public function CategoryDetails($id)
    {
        $category = Category::find($id);
        $category->load('Product');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $category,
        ]);
    }
}
