<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:sellers,email',
            'password' => 'required|string|min:3',
        ]);
        if (!$validator->fails()) {
            return  $this->genaratePGCt($request);
        } else {
            return response()->json(
                ['message' => 'Login Filed, chick your data!'],
                Response::HTTP_BAD_REQUEST,
            );
        }
    }

    private function genaratePGCt(Request $request)
    {
        try {

            $response = Http::asForm()->post('http://127.0.0.1:81/oauth/token', [
                'grant_type' => 'password',
                'client_id' => '4',
                'client_secret' => 'rDzEbGGs6hTe7p8ZUsN9c2dnlLMkBpBGVQJN03tP',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '*',

            ]);

            $decodedResponse = json_decode($response);
            $seller = Seller::where('email', '=', $request->input('email'))->first();
            $seller->setAttribute('token', $decodedResponse->access_token);
            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'data' => $seller,
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
        $guard = auth('seller')->check() ? $guard : null;
        $token = $request->user('seller')->token();
        $revoked = $token->revoke();
        return response()->json(
            [
                'message' => $revoked ? 'Signed out successfully' : 'Logout failed',
            ],
            $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }
}
