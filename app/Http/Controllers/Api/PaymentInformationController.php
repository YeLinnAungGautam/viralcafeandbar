<?php

namespace App\Http\Controllers\Api;

use App\Class\PaymentInfo\PaymentInfoQuery;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PaymentInformationResource;
use App\Models\PaymentInfo;
use Illuminate\Http\Request;

class PaymentInformationController extends Controller
{

    public function index()
    {
        $payment_infos = new PaymentInfoQuery();

        return ResponseJson::success(PaymentInformationResource::collection($payment_infos->limit()->get()));
    }
}
