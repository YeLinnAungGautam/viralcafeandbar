<?php

namespace App\Http\Controllers\Api;

use App\Class\SendOtpCode;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResendOtpTrait;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    use ResendOtpTrait;

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'code'       => 'required|numeric',
            'credential' => 'required',
        ]);

        $code = Cache::get('verify_crendential' . $request->credential);

        if ($code != $request->code) {
            return ResponseJson::error('Your OTP code is invalid');
        } else {
            return ResponseJson::success(null, 'Success');
        }

    }

    public function createNewPassword(Request $request)
    {
        $request->validate([
            'code'      => 'required|numeric',
            'password'  => 'required|min:8|confirmed',
            'fcm_token' => 'required',
        ]);

        $code = Cache::get('verify_crendential' . $request->credential);

        if ($code != $request->code) {

            return ResponseJson::error('Your OTP code is invalid');

        }

        if ($code == $request->code) {

            Cache::forget('verify_crendential' . $request->credential);

            $col_name = is_numeric($request->credential) ? 'phone' : 'email';

            $customer = Customer::active()
                ->where($col_name, $request->credential)
                ->first();

            if (is_null($customer)) {
                return ResponseJson::error('Your account was not found!');
            }

            $customer->update([
                'password' => Hash::make($request->password),
            ]);

            return ResponseJson::success(null, 'Password Successfully Created!.');
        }
    }

    // public function sendOTP(Request $request)
    // {
    //     $this->resend($request);

    // }
}
