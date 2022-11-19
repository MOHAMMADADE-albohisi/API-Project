<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    //

    public function index()
    {

        $products =  Product::where('status', '=', 'Visible')->get();
        return response()->json([
            'status' => true,
            'message' => "Success",
            'list' => $products,
        ]);
    }

    public function Store(Request $request, Product $product)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:5',
            'price' => 'required|numeric',
            'status' => 'required|string|in:Visible,InVisible',

        ]);
        if (!$validator->fails()) {
            $product = new Product();
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->status = $request->input('status');
            $isSaved = $product->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'Product created successfully' : 'Product Create failed'
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
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:5',
            'price' => 'required|numeric',
            'status' => 'required|string|in:Visible,InVisible',
        ]);

        if (!$validator->fails()) {
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->status = $request->input('status');
            $isSaved = $product->save();
            return response()->json(
                ['message' => $isSaved ? ' update Driver successfully' : ' update Driver failed'],
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
                'message' => $deleted ? 'Deleted successfully' : 'Deleted failled ',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }
}
