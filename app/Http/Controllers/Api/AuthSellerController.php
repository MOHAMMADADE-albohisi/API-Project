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

class AuthSellerController extends Controller
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
                ['message' => 'فشل تسجيل الدخول ، تحقق من بياناتك!'],
                Response::HTTP_BAD_REQUEST,
            );
        }
    }

    private function genaratePGCt(Request $request)
    {
        try {

            $response = Http::asForm()->post('http://127.0.0.1:81/oauth/token', [
                'grant_type' => 'password',
                'client_id' => '3',
                'client_secret' => 'cNAtZweBdShS20ewCNj8AAaPMHLX8iX3xpV38kIC',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '*',

            ]);

            $decodedResponse = json_decode($response);
            $seller = Seller::where('email', '=', $request->input('email'))->first();
            $seller->setAttribute('token', $decodedResponse->access_token);
            $seller->load('store');
            return response()->json([
                'status' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
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
                'message' => $revoked ? 'تم تسجيل الخروج بنجاح' : 'فشل تسجيل الخروج',
            ],
            $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }


    public function store(Request $request, Seller $seller)
    {
        //
        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'full_name' => 'required|string|min:3',
            'email' =>  'required|string|unique:sellers',
            'mobile' => 'required|numeric',
            'Address' => 'required|string|min:5',
            'password' => 'required|string|min:3',



        ]);
        if (!$validator->fails()) {
            $seller = new Seller();
            $seller->store_id = $request->input('store_id');
            $seller->full_name = $request->input('full_name');
            $seller->email = $request->input('email');
            $seller->mobile = $request->input('mobile');
            $seller->Address = $request->input('Address');
            $seller->password = Hash::make($request->input('password'));
            $isSaved = $seller->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم انشاء حساب البائع بنجاح' : 'فشل انشاء الحساب الخاص بلبائع'
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
