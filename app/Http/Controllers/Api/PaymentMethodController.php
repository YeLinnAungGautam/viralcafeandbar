<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $payment_methods = Payment::active()
            ->latest('id')
            ->limit(5)
            ->get();

        return ResponseJson::success(PaymentResource::collection($payment_methods)); 
    }
}
