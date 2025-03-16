<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionResource;
use App\Jobs\PromotionNotificationJob;
use App\Models\Customer;
use App\Models\CustomerDeviceToken;
// use App\Models\Notification;
use App\Models\Promotion;
use App\Notifications\Promotion as NotificationsPromotion;
use App\Services\FcmNotifyService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    //
    public function index()
    {
        $promotions = Promotion::active()->latest('id')->paginate(10);
        return response()->json([
            'total'          => $promotions->total(),
            'current_page'   => $promotions->currentPage(),
            'per_page'       => $promotions->perPage(),
            'data'           => PromotionResource::collection($promotions->items()),
            'has_more_pages' => $promotions->hasMorePages(),
        ]);
    }
    public function show($id)
    {
        $promotion = Promotion::active()->find($id);

        if (!$promotion) {
            return ResponseJson::error('Promotion was not found.', 404);
        } else {
            return ResponseJson::success(new PromotionResource($promotion));
        }
    }

    public function notify(Request $request)
    {
        try {
            $data = collect($request->all())->except('translates')->toArray();

            (new FcmNotifyService())->sendWithTopic('public', $request->title, $request->body, $data);

            PromotionNotificationJob::dispatch($request->all());

            return ResponseJson::success([], 'success');
        } catch (\Exception $th) {
            return ResponseJson::error($th->getMessage());
        }
    }

    public function testNoti(Request $request)
    {
        try {
            $tokens = CustomerDeviceToken::whereIn('customer_id', $request->customer_id)->pluck('token')->toArray();
            $data = collect($request->all())->except('translates')->toArray();


            foreach ($tokens as $key => $value) {
                (new FcmNotifyService())->sendWithDeviceToken($value, $request->title, $request->body, $data);
            }
            return ResponseJson::success($tokens, 'success');
        } catch (\Exception $e) {
            return ResponseJson::error($e->getMessage());
        }
    }
}
