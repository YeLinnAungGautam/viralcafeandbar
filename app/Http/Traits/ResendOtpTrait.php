<?php

namespace App\Http\Traits;

use App\Class\SendOtpCode;
use App\Helpers\ResponseJson;
use App\Models\Customer;
use Illuminate\Http\Request;

trait ResendOtpTrait
{
    public function sendOTP(Request $request)
    {
        $request->validate([
            'credential' => 'required',
        ]);

        $col_name = is_numeric($request->credential) ? 'phone' : 'email';

        $exitCredential = Customer::where($col_name, $request->credential)->exists();

        if (!$exitCredential) {
            return ResponseJson::error('Your credential was not exists');
        }

        try {
            $otpSent = new SendOtpCode($request);

            return $otpSent->send();

        } catch (\Exception $e) {
            return ResponseJson::error($e->getMessage());
        }
    }
}
