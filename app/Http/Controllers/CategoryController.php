<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categorys = Category::where('status', '=', 'Visible')->get();
        return response()->view('cms.subcategoryList-option', ['categorys' => $categorys]);
    }

    public function indexApi()
    {
        //
        $categorys = Category::where('status', '=', 'Visible')->get();
        return response()->json([
            'stuts' => true,
            'masseg' => 'Success',
            'data' => $categorys,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $store = Store::all();
        return response()->view('cms.create-category', ['store' => $store]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator($request->all(), [
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',
            'status' => 'required|string|min:3',
            'image' => 'required|image|mimes:jpg,png',

        ]);

        if (!$validator->fails()) {
            $category = new Category();
            $category->title = $request->input('title');
            $category->description = $request->input('description');
            $category->status = $request->input('status');
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . str_replace(' ', '', $category->name) . '.' . $request->file('image')->extension();
                $request->file('image')->storePubliclyAs('categorys', $imageName, ['disk' => 'public']);
                $category->image = 'categorys/' . $imageName;
            }
            $isSaved = $category->save();
            return Response()->json(

                ['message' => $isSaved ? 'تم انشاء المنتج بنجاح' : 'فشل انشاء المنتتج'],

                $isSaved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
            );
        } else {
            dd($validator->getMessageBag()->first());
            return response()->json(["message" => $validator->getMessageBag()->first()], Response::HTTP_BAD_REQUEST);
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        //
    }
}
