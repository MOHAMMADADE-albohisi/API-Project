<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class BuyerController extends Controller
{
    //

    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:buyers,email',
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
                'client_id' => '5',
                'client_secret' => 'xASKhlTPy74BrwfpWOfYUP158lxNCT6JFhpQtl9u',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '*',

            ]);

            $decodedResponse = json_decode($response);
            $buyer = Buyer::where('email', '=', $request->input('email'))->first();
            $buyer->setAttribute('token', $decodedResponse->access_token);
            return response()->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'data' => $buyer,
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
        $guard = auth('buyer')->check() ? $guard : null;
        $token = $request->user('buyer')->token();
        $revoked = $token->revoke();
        return response()->json(
            [
                'message' => $revoked ? 'Signed out successfully' : 'Logout failed',
            ],
            $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }


    public function store(Request $request, Buyer $buyer)
    {
        //
        $validator = validator($request->all(), [
            'name' => 'required|string|min:3',
            'email' =>  'required|string|unique:sellers',
            'mobile' => 'required|numeric',
            'password' => 'required|string|min:3',

        ]);
        if (!$validator->fails()) {
            $buyer = new Buyer();
            $buyer->name = $request->input('name');
            $buyer->email = $request->input('email');
            $buyer->mobile = $request->input('mobile');
            $buyer->password = Hash::make($request->input('password'));
            $isSaved = $buyer->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'Seller created successfully' : 'Seller Create failed'
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
