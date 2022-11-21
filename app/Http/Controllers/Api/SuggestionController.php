<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SuggestionController extends Controller
{
    //

    public function index(Request $request)
    {

        $suggestions = Suggestion::withCount(['buyer'])
            ->whereHas('buyer', function ($query) use ($request) {
                $query->where('buyer_id', '=', $request->user()->id);
            })->get();
        $suggestions->load('buyer');
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => $suggestions,
        ]);
    }

    public function Store(Request $request, Suggestion $suggestions)
    {
        $validator = validator($request->all(), [
            'titel' => 'required|string|min:3',
            'subtitle' => 'required|string|min:5',
        ]);
        if (!$validator->fails()) {
            $suggestions = new Suggestion();
            $suggestions->titel = $request->input('titel');
            $suggestions->subtitle = $request->input('subtitle');
            $buyer = Auth::guard('buyer')->user();
            $suggestions->buyer_id = $buyer->id;
            $isSaved = $suggestions->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم ارسال اقتراحك الى مدير المتجر ' : 'فشل ارسال الاقتراح'
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


    public function Update(Request $request, $id)
    {
        $suggestions = Suggestion::find($id);
        $validator = validator($request->all(), [
            'titel' => 'required|string|min:3',
            'subtitle' => 'required|string|min:5',
        ]);
        if (!$validator->fails()) {
            $suggestions->titel = $request->input('titel');
            $suggestions->subtitle = $request->input('subtitle');
            $buyer = Auth::guard('buyer')->user();
            $suggestions->buyer_id = $buyer->id;
            $isSaved = $suggestions->save();
            return response()->json(
                [

                    'message' => $isSaved ? 'تم تعديل اقتراحك بنجاح' : 'فشل عمليه التعديل'
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


    public function destroy($id)
    {
        $suggestions = Suggestion::find($id);
        $deleted = $suggestions->delete();
        return response()->json(
            [
                'message' => $deleted ? 'تم حدف اقتراحك' : 'فشلت عمليه حدف الاقتراح ',
            ],
            $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST

        );
    }
}
