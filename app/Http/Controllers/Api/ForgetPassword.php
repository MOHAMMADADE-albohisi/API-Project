<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BuyerForgetPasswordEmail;
use App\Mail\DriverForgetPasswordEmail;
use App\Mail\SellerForgetPasswordEmail;
use App\Models\Buyer;
use App\Models\Driver;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\Password;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Symfony\Component\HttpFoundation\Response;

class ForgetPassword extends Controller
{
    
    public function forgotPasswordSeller(Request $request)
    {

        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:sellers,email',
        ]);

        if (!$validator->fails()) {
            $code = random_int(1000, 9999);
            $seller = Seller::where('email', '=', $request->input('email'))->first();
            $seller->verificcation_code = Hash::make($code);
            $isSaved = $seller->save();
            if ($isSaved) {
                Mail::to($seller)->send(new SellerForgetPasswordEmail($seller, $code));
            }
            return response()->json(
                [
                    'status' => $isSaved,
                    'message' => $isSaved ? 'تم ارسال كود الاستعادة بنجاح' : 'فشل ارسال كود',
                    'code' => $code,
                ],
                $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validator->getMessageBag()->first(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function resetPasswordSeller(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:sellers,email',
            'code' => 'required|numeric|digits:4',
            'new_password' => [
                'required', 'string', Password::min(6)
                    ->letters()
                    ->symbols()
                    ->numbers()
                    ->mixedCase()
                    ->uncompromised()
            ]
        ]);


        if (!$validator->fails()) {
            $seller = Seller::where('email', '=', $request->input('email'))->first();
            if (Hash::check($request->input('code'), $seller->verificcation_code)) {
                $seller->password = Hash::make($request->input('new_password'));
                $isSaved = $seller->save();
                return response()->json(
                    [
                        'message' => $isSaved ? 'تم تغير كلمة المرور بنجاح' : 'فشلت عملية تغير كلمة المرور'
                    ],
                    $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
                );
            } else {
                return response()->json(
                    [
                        'stuts' => false,
                        'message' => 'الرجاء التأكد من الرمز المرسل'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } else {
            return response()->json(
                [
                    'message' => $validator->getMessageBag()->first()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }


    public function forgotPasswordBuyer(Request $request)
    {

        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:buyers,email',
        ]);

        if (!$validator->fails()) {
            $code = random_int(1000, 9999);
            $buyer = Buyer::where('email', '=', $request->input('email'))->first();
            $buyer->verificcation_code = Hash::make($code);
            $isSaved = $buyer->save();
            if ($isSaved) {
                Mail::to($buyer)->send(new BuyerForgetPasswordEmail($buyer, $code));
            }
            return response()->json(
                [
                    'status' => $isSaved,
                    'message' => $isSaved ? 'تم ارسال كود الاستعادة بنجاح' : 'فشل ارسال كود',
                    'code' => $code,
                ],
                $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validator->getMessageBag()->first(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function resetPasswordBuyer(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:buyers,email',
            'code' => 'required|numeric|digits:4',
            'new_password' => [
                'required', 'string', Password::min(6)
                    ->letters()
                    ->symbols()
                    ->numbers()
                    ->mixedCase()
                    ->uncompromised()
            ]
        ]);


        if (!$validator->fails()) {
            $buyer = Buyer::where('email', '=', $request->input('email'))->first();
            if (Hash::check($request->input('code'), $buyer->verificcation_code)) {
                $buyer->password = Hash::make($request->input('new_password'));
                $isSaved = $buyer->save();
                return response()->json(
                    [
                        'message' => $isSaved ? 'تم تغير كلمة المرور بنجاح' : 'فشلت عملية تغير كلمة المرور'
                    ],
                    $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
                );
            } else {
                return response()->json(
                    [
                        'stuts' => false,
                        'message' => 'الرجاء التأكد من الرمز المرسل'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } else {
            return response()->json(
                [
                    'message' => $validator->getMessageBag()->first()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }



    public function forgotPasswordDriver(Request $request)
    {

        $validator = Validator($request->all(), [
            'email' => 'required|email|exists:drivers,email',
        ]);

        if (!$validator->fails()) {
            $code = random_int(1000, 9999);
            $driver = Driver::where('email', '=', $request->input('email'))->first();
            $driver->verificcation_code = Hash::make($code);
            $isSaved = $driver->save();
            if ($isSaved) {
                Mail::to($driver)->send(new DriverForgetPasswordEmail($driver, $code));
            }
            return response()->json(
                [
                    'status' => $isSaved,
                    'message' => $isSaved ? 'تم ارسال كود الاستعادة بنجاح' : 'فشل ارسال كود',
                    'code' => $code,
                ],
                $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validator->getMessageBag()->first(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function resetPasswordDriver(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email|exists:drivers,email',
            'code' => 'required|numeric|digits:4',
            'new_password' => [
                'required', 'string', Password::min(6)
                    ->letters()
                    ->symbols()
                    ->numbers()
                    ->mixedCase()
                    ->uncompromised()
            ]
        ]);


        if (!$validator->fails()) {
            $driver = Driver::where('email', '=', $request->input('email'))->first();
            if (Hash::check($request->input('code'), $driver->verificcation_code)) {
                $driver->password = Hash::make($request->input('new_password'));
                $isSaved = $driver->save();
                return response()->json(
                    [
                        'message' => $isSaved ? 'تم تغير كلمة المرور بنجاح' : 'فشلت عملية تغير كلمة المرور'
                    ],
                    $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
                );
            } else {
                return response()->json(
                    [
                        'stuts' => false,
                        'message' => 'الرجاء التأكد من الرمز المرسل'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
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
