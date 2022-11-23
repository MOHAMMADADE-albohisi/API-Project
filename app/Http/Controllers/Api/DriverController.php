<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\OrderDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller
{
    //
    public function index()
    {
        $drivers =  Driver::all();
        $drivers->load('stroe');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'list' => $drivers,
        ]);
    }

    public function Store(Request $request, Driver $driver)
    {
        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'full_name' => 'required|string|min:3',
            'email' =>  'required|string|unique:drivers',
            'mobile' => 'required|string',
            'password' => 'required|string|min:3',

        ]);
        if (!$validator->fails()) {
            $driver = new Driver();
            $driver->store_id = $request->input('store_id');
            $driver->full_name = $request->input('full_name');
            $driver->email = $request->input('email');
            $driver->mobile = $request->input('mobile');
            $driver->password = Hash::make($request->input('password'));
            $isSaved = $driver->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم إنشاء حساب السائق بنجاح' : 'فشل إنشاء حساب السائق'
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


    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);
        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'full_name' => 'required|string|min:3',
            'email' =>  'required|string',
            'mobile' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $driver->store_id = $request->input('store_id');
            $driver->full_name = $request->input('full_name');
            $driver->email = $request->input('email');
            $driver->mobile = $request->input('mobile');
            $isSaved = $driver->save();
            return response()->json(
                ['message' => $isSaved ? 'تم تحديث حساب السائق بنجاح' : 'فشل تحديث حساب السائق  '],
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
        $product = Driver::find($id);
        $deleted = $product->delete();
        return response()->json(
            [
                'message' => $deleted ? 'تم حذف حساب السائق بنجاح' : 'فشل الحذف ',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }


    public function order(Request $request)
    {
        $orderDriver = OrderDriver::withCount(['seller', 'orderProduct', 'driver'])
            ->whereHas('driver', function ($query) use ($request) {
                $query->where('driver_id', '=', $request->user()->id);
            })->get();
        $orderDriver->load('orderProduct');

        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $orderDriver,
        ]);
    }



    public function OrderDriver($id)
    {
        $orderdriver = OrderDriver::find($id);
        if ($orderdriver !== null) {
            $orderdriver->load('orderProduct');
            $orderdriver->load('driver');
            $orderdriver->load('product');
            $orderdriver->load('buyer');
            $orderdriver->load('store');
            return response()->json([
                'status' => true,
                'message' => "Success",
                'data' => $orderdriver,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Fali",
                'data' => 'عذا لا يوجد طلب بهدا الاسم',
            ]);
        }
    }
}
