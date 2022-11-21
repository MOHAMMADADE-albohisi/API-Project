<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    //

    public function index()
    {

        $stores =  Store::where('active', '=', true)->get();
        return response()->json([
            'status' => true,
            'message' => "Success",
            'list' => $stores,
        ]);
    }

    public function Store(Request $request, Store $store)
    {
        $validator = validator($request->all(), [
            'name_en' => 'required|string|min:3',
            'name_ar' => 'required|string|min:5',
            'active' => 'required|boolean',
        ]);
        if (!$validator->fails()) {
            $store = new Store();
            $store->name_en = $request->input('name_en');
            $store->name_ar = $request->input('name_ar');
            $store->active = $request->input('active');
            $isSaved = $store->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم إنشاء المتجر بنجاح' : 'فشل إنشاء المتجر'
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
        $store = Store::find($id);
        $validator = validator($request->all(), [
            'name_en' => 'required|string|min:3',
            'name_ar' => 'required|string|min:5',
            'active' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            $store->name_en = $request->input('name_en');
            $store->name_ar = $request->input('name_ar');
            $store->active = $request->input('active');
            $isSaved = $store->save();
            return response()->json(
                ['message' => $isSaved ? 'تم التعديل بنجاح' : 'فشل التعديل'],
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
        $store = Store::find($id);
        $deleted = $store->delete();
        return response()->json(
            [
                'message' => $deleted ? 'تم حذف المتجر بنجاح' : 'فشل الحذف ',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }
}
