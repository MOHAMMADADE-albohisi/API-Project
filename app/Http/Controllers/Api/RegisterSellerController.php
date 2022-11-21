<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RegisterSellerController extends Controller
{
    //
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
