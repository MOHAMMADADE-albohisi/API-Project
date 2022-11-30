<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nafezly\Payments\Classes\PayPalPayment;
use Symfony\Component\HttpFoundation\Response;

class PayPalPaymentController extends Controller
{
    //
    public function sendPayment(Request $request)
    {
        $paypal = new PayPalPayment();
        $res = (object) $paypal->pay(
            "500"
            // auth()->user()->id,
            // auth()->user()->name,
            // auth()->user()->email,
        );
        if ($res->success) {
            return response()->json([
                'stuts' => true,
                'message' => 'سيتم تحويلك الى صفحة الدفع ',
                'redirect_url' =>  $res->redirect_url,
            ]);
        } else {
            return response()->json([
                'stauts' => false,
                'message' => 'حدث خطأ أثناء تنفيذ العملية ، حاول في وقت لاحق'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function payment_verify(Request $request, $payment)
    {
        $paypal = new PayPalPayment();
        $res = (object) $paypal->verify($request);
        // قم بما تريد ، غير نوع الحالة للبرودكت / سجل قيمة الدفع في الداتا بيز اللي بدك اياه بتقدر تطبع الرسبونس كله وشوف ايش بلزمك منه
        if ($res->success) {
            
        } else {
            return response()->json([
                'stauts' => false,
                'message' => 'حدث خطأ أثناء تنفيذ العملية ، حاول في وقت لاحق'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
