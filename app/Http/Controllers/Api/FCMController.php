<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\CustomerDeviceToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FCMController extends Controller
{
    public function save(Request $request)
    {
        $customer = Customer::findOrFail(Auth::user()->id);

        $customer->update([
            'is_notification' => !$customer->is_notification,
        ]);

        $message = $customer->is_notification ? 'allowed' : 'disabled';

        return ResponseJson::success(null, "Notification was {$message}.", 200);
    }
}
