<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Dotenv\Loader\Loader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthBuyerController extends Controller
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
                'client_id' => '4',
                'client_secret' => 'yyE7Q0AhSCAI7vIByWnGDo8VB3XuLQa2Hv0tdkRa',
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'scope' => '*',

            ]);

            $decodedResponse = json_decode($response);
            $buyer = Buyer::where('email', '=', $request->input('email'))->first();
            $buyer->setAttribute('token', $decodedResponse->access_token);
            $buyer->load('store');
            return response()->json([
                'status' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
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
                'message' => $revoked ? 'تم تسجيل الخروج بنجاح' : 'فشل تسجيل الخروج',
            ],
            $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }


    public function store(Request $request, Buyer $buyer)
    {
        //
        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'name' => 'required|string|min:3',
            'email' =>  'required|string|unique:buyers',
            'mobile' => 'required|numeric',
            'password' => 'required|string|min:3',
            'image' => 'required|image|mimes:jpg,png',


        ]);
        if (!$validator->fails()) {
            $buyer = new Buyer();
            $buyer->store_id = $request->input('store_id');
            $buyer->name = $request->input('name');
            $buyer->email = $request->input('email');
            $buyer->mobile = $request->input('mobile');
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . str_replace(' ', '', $buyer->name) . '.' . $request->file('image')->extension();
                $request->file('image')->storePubliclyAs('buyer', $imageName, ['disk' => 'public']);
                $buyer->image = 'buyer/' . $imageName;
            }
            $buyer->password = Hash::make($request->input('password'));
            $isSaved = $buyer->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم إنشاء حسابك  بنجاح' : 'فشل إنشاء مشتري'
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


    public function profiel($id)
    {
        if ($buyers = Buyer::find($id)) {
            return response()->json([
                'stuts' => true,
                'message' => 'Success',
                'data' => $buyers,
            ]);
        } else {
            return response()->json([
                'stuts' => false,
                'message' => 'Fail',
                'data' => 'عذرا لا يوجد حساب بهذا المعرف',
            ]);
        }
    }


    public function EditProfiel(Request $request, Buyer $buyer, $id)
    {
        $buyer = Buyer::find($id);
        $validator = validator($request->all(), [
            'store_id' => 'required|numeric|exists:stores,id',
            'name' => 'required|string|min:3',
            'email' =>  'required|string',
            'mobile' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,png',
        ]);
        if (!$validator->fails()) {
            $buyer->store_id = $request->input('store_id');
            $buyer->name = $request->input('name');
            $buyer->email = $request->input('email');
            $buyer->mobile = $request->input('mobile');
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . str_replace(' ', '', $buyer->name) . '.' . $request->file('image')->extension();
                $request->file('image')->storePubliclyAs('buyer', $imageName, ['disk' => 'public']);
                $buyer->image = 'buyer/' . $imageName;
            }
            $isSaved = $buyer->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم تحرير ملف الشخصي بنجاح' : 'فشل تحديث الحساب'
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
