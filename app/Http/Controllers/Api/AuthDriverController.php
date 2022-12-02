<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Dotenv\Loader\Loader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthDriverController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:drivers,email',
            'password' => 'required|string|min:3',
        ]);
        if (!$validator->fails()) {
            return  $this->generatePGCT($request);
        } else {
            return response()->json(
                ['message' => 'فشل تسجيل الدخول ، تحقق من بياناتك!'],
                Response::HTTP_BAD_REQUEST,
            );
        }
    }

    private function generatePGCT(Request $request)
    {
        try {
            $response = Http::asForm()->post('http://127.0.0.1:81/oauth/token', [
                'grant_type' => 'password',
                'client_id' => '5',
                'client_secret' => 'ntu5h6fai4ztH75aRvdTuTDvMarMohhi1wzuYHRe',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '*'
            ]);
            $decodedResponse = json_decode($response);
            $drivers = Driver::where('email', '=', $request->input('email'))->first();
            $drivers->setAttribute('token', $decodedResponse->access_token);
            return response()->json([
                'status' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => $drivers,
            ], Response::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => json_decode($response)->message,
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function logout(Request $request)
    {
        $guard = session('guard');
        $guard = auth('driver')->check() ? $guard : null;
        $token = $request->user('driver')->token();
        $revoked = $token->revoke();
        return response()->json(
            [
                'message' => $revoked ? 'تم تسجيل الخروج بنجاح' : 'فشل تسجيل الخروج',
            ],
            $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }


    public function profiel($id)
    {

        if ($drivers = Driver::find($id)) {
            return response()->json([
                'stuts' => true,
                'message' => 'Success',
                'data' => $drivers,
            ]);
        } else {
            return response()->json([
                'stuts' => false,
                'message' => 'Fail',
                'data' => 'عذرا لا يوجد حساب بهذا المعرف',
            ]);
        }
    }


    public function EditProfiel(Request $request, Driver $driver, $id)
    {
        $driver = Driver::find($id);
        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'full_name' => 'required|string|min:3',
            'email' =>  'required|string',
            'mobile' => 'required|string',
            'image' => 'required|image|mimes:jpg,png',

        ]);
        if (!$validator->fails()) {
            $driver->store_id = $request->input('store_id');
            $driver->full_name = $request->input('full_name');
            $driver->email = $request->input('email');
            $driver->mobile = $request->input('mobile');
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . str_replace(' ', '', $driver->name) . '.' . $request->file('image')->extension();
                $request->file('image')->storePubliclyAs('driver', $imageName, ['disk' => 'public']);
                $driver->image = 'driver/' . $imageName;
            }
            $isSaved = $driver->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم تحرير ملف التعريف بنجاح' : 'فشل تحرير ملف التعريف'
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
}
