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
                'client_secret' => '8s3Yne5gUBAl7MJui5FCD6mepkdr7HFlQqHOlB8T',
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
}
