<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends Controller
{
    //
    public function index()
    {
        $drivers =  Driver::all();
        return response()->json([
            'status' => true,
            'message' => "Success",
            'list' => $drivers,
        ]);
    }

    public function Store(Request $request, Driver $driver)
    {
        $validator = validator($request->all(), [
            'full_name' => 'required|string|min:3',
            'email' =>  'required|string|unique:drivers',
            'mobile' => 'required|string',
            'password' => 'required|string|min:3',

        ]);
        if (!$validator->fails()) {
            $driver = new Driver();
            $driver->full_name = $request->input('full_name');
            $driver->email = $request->input('email');
            $driver->mobile = $request->input('mobile');
            $driver->password = Hash::make($request->input('password'));
            $isSaved = $driver->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'Driver created successfully' : 'Driver Create failed'
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
            'full_name' => 'required|string|min:3',
            'email' =>  'required|string|unique:drivers',
            'mobile' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $driver->full_name = $request->input('full_name');
            $driver->email = $request->input('email');
            $driver->mobile = $request->input('mobile');
            $isSaved = $driver->save();
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
        $product = Driver::find($id);
        $deleted = $product->delete();
        return response()->json(
            [
                'message' => $deleted ? 'Deleted successfully' : 'Deleted failled ',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }
}
