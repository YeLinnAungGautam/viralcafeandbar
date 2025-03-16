<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\FcmNotifyService;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $id = Auth::id();

        $customer = Customer::find($id);

        $notifications = $customer->notifications()->paginate(10);

        return response()->json([
            'total'          => $notifications->total(),
            'current_page'   => $notifications->currentPage(),
            'per_page'       => $notifications->perPage(),
            'data'           => $notifications->items(),
            'has_more_pages' => $notifications->hasMorePages(),
        ]);
    }

    public function read($id)
    {
        $authId = Auth::id();

        $customer = Customer::find($authId);

        if ($id == 'all') {
            $notifications = $customer->notifications;

            foreach ($notifications as $key => $notification) {
                $notification->markAsRead();
            }

            return ResponseJson::success(null, 'Notifications was readed all.');
        } else {
            $notification = $customer->notifications()->find($id);

            if ($notification) {
                $notification->markAsRead();

                return ResponseJson::success(null, 'Notifications was readed');
            } else {
                return ResponseJson::error('Notification not found.', 404);
            }
        }
    }

    public function unreadCount()
    {
        $id           = Auth::id();
        $customer     = Customer::find($id);
        $notification = $customer->notifications()->unread()->count();

        return ResponseJson::success($notification);
    }

    public function getToken(Request $request)
    {
        if ($request->header('API-TOKEN')) {
            $token = $request->header('API-TOKEN');

            $data = verifyHmacWithExpiry($token);

            return response()->json([
                'data' => $data,
            ]);
        }
    }
}
