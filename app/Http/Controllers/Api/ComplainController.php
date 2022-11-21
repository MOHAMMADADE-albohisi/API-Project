<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ComplainController extends Controller
{
    //
    public function index(Request $request)
    {

        $complains = Complain::withCount(['buyer'])
            ->whereHas('buyer', function ($query) use ($request) {
                $query->where('buyer_id', '=', $request->user()->id);
            })->get();
        $complains->load('buyer');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $complains,
        ]);
    }

    public function Store(Request $request, Complain $complains)
    {
        $validator = validator($request->all(), [
            'titel' => 'required|string',
            'subtitle' => 'required|string',
        ]);
        if (!$validator->fails()) {
            $complains = new Complain();
            $complains->titel = $request->input('titel');
            $complains->subtitle = $request->input('subtitle');
            $buyer = Auth::guard('buyer')->user();
            $complains->buyer_id = $buyer->id;
            $isSaved = $complains->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم ارسال شكوتك الخاصة الى مدير المتجر ' : 'فشل ارسال الاقتراح'
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
