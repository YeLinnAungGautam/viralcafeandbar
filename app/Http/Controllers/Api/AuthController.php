<?php

namespace App\Http\Controllers\Api;

use App\Class\Customer\CustomerQuery;
use App\Models\Customer;
use App\Class\SendOtpCode;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Services\CustomerService;
use App\Models\CustomerDeviceToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Traits\SaveCustomerTrait;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\CustomerResource;
use App\Http\Traits\ResendOtpTrait;
use App\Models\MemberShip;

class AuthController extends Controller
{

    use SaveCustomerTrait;

    public function index()
    {
        $id            = Auth::id();
        $customerQuery = new CustomerQuery();
        $customer      = $customerQuery->getProfile($id);

        return ResponseJson::success(new CustomerResource($customer));
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::id();

        $request->validate([
            'first_name'   => 'nullable|string',
            'last_name'    => 'nullable|string',
            'username'     => 'required|regex:/^[a-zA-Z][a-zA-Z0-9_-]{2,14}$/|unique:customers,username,' . $id,
            'email'        => 'nullable|required_without:phone|email|unique:customers,email,' . $id,
            'phone'        => 'nullable|required_without:email|nullable|unique:customers,phone,' . $id,
            'meta.address' => 'nullable',
            'meta.country' => 'nullable',
            'meta.gender'  => 'nullable',
            'meta.dob'     => 'nullable|date_format:d-m-Y',
        ]);

        $customer = Customer::find($id);

        $data = (new CustomerService)->update($customer, $request);

        $this->saveMeta($request->meta, $data);

        $data->load(['customerMeta', 'customerMemberShip']);

        return ResponseJson::success(new CustomerResource($data), 'Profile update successful.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Your password is incorrect.',
        ]);

        $id = Auth::id();

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return ResponseJson::error('Current password was not invalid.');
        }

        $customer           = Customer::find($id);
        $customer->password = Hash::make($request->password);
        $customer->update();

        return ResponseJson::success($customer, 'Change password successful.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'credential' => 'required',
            'password'   => 'required|string|min:8',
            'fcm_token'  => 'required',
        ], [
            'password.min' => 'Your password is incorrect.',
        ]);

        $col = is_numeric($request->credential) ? "phone" : "email";

        $user = Customer::where($col, $request->credential)
            ->first();

        if ($user) {
            if ($user->status == 'draft') {
                return ResponseJson::error(__('Your account was not active'));
            }

            if (!Hash::check($request->password, hashedValue: $user->password)) {
                return ResponseJson::error(__('Wrong password'));
            }

            $token = $user->createToken('UserToken')->plainTextToken;

            $this->_createDeviceToken($request->fcm_token, $user);

            return response()->json([
                'success' => true,
                'user'    => $user,
                'data'    => $token,
            ])->header('Authorization', $token);
        } else {
            return ResponseJson::error(__('No user found with this credential'));
        }
    }

    public function chnageCredential(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
        ]);

        $customer = Auth::user();

        if ($customer->email !== $request->email || $customer->phone !== $request->phone) {

            $existEmail = Customer::where('email', $request->email)
                ->whereNotNull('email')
                ->exists();

            $existPhone = Customer::where('phone', $request->phone)
                ->whereNotNull('phone')
                ->exists();

            if ($existEmail) {
                return ResponseJson::error('Email address already taken.');
            }

            if ($existPhone) {
                return ResponseJson::error('Phone number already taken.');
            }

            if ($request->email) {
                $request->merge([
                    'credential' => $request->email,
                ]);
            }

            if ($request->phone) {
                $request->merge([
                    'credential' => $request->phone,
                ]);
            }

            try {
                return (new SendOtpCode($request))
                    ->send();
            } catch (\Exception $e) {
                return ResponseJson::error($e->getMessage());
            }
        } else {
            return ResponseJson::success(null);
        }
    }

    public function verifyCredential(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
            'code'  => 'required',
        ]);

        $credential = $request->email ?: $request->phone;
        $code       = Cache::get('verify_crendential' . $credential);

        if ($code != $request->code) {
            return ResponseJson::error('Your OTP code is invalid');
        }

        $authId = Auth::id();

        $customer = Customer::find($authId);

        if ($code == $request->code) {
            if ($request->email) {
                $customer->update([
                    'email' => $request->email,
                ]);

                return ResponseJson::success($customer, 'Email changes was successful.');
            }

            if ($request->phone) {
                $customer->update([
                    'phone' => $request->phone,
                ]);

                return ResponseJson::success($customer, 'Phone number changes was successful.');
            }
        }
    }

    public function register(RegisterRequest $request)
    {
        $otpSent = new SendOtpCode($request);
        try {

            $col_name = is_numeric($request->credential) ? 'phone' : 'email';

            $customer = Customer::where($col_name, $request->credential)->exists();

            if ($customer) {
                return ResponseJson::error('Your credential was already exists.', 400);
            }

            return $otpSent->send();
        } catch (\Exception $e) {
            return ResponseJson::error($e->getMessage());
        }
    }

    public function sendOTP(Request $request)
    {
        try {
            $otpSent = new SendOtpCode($request);

            return $otpSent->send();

        } catch (\Exception $e) {
            return ResponseJson::error($e->getMessage());
        }
    }

    public function tokenUpdate(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required',
        ]);

        $customer = Customer::find(Auth::id());
        $this->_createDeviceToken($request->fcm_token, $customer);

        return ResponseJson::success($request->all(), 'Token was updated.');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'code'       => 'required|numeric',
            'credential' => 'required',
            'fcm_token'  => 'required',
        ]);

        $code = Cache::get('verify_crendential' . $request->credential);

        if ($code != $request->code) {
            return ResponseJson::error('Your OTP code is invalid');
        }

        if ($code == $request->code) {
            Cache::forget('verify_crendential' . $request->credential);

            $customer = Customer::create([
                'username' => $request->username,
                'email'    => !is_numeric($request->credential) ? $request->credential : null,
                'phone'    => is_numeric($request->credential) ? $request->credential : null,
                'password' => Hash::make($request->password),
                'status'   => 'active',
            ]);

            (new CustomerService())->addMemberShip($customer);

            $token = $customer->createToken('UserToken')->plainTextToken;

            $this->_createDeviceToken($request->fcm_token, $customer);

            return ResponseJson::success($token, 'Account create successful.')
                ->header('Authorization', $token);
        }
    }

    public function getPointLevel()
    {
        $id = Auth::id();

        $customer = Customer::with(['customerMemberShip'])->find($id);

        $membership = MemberShip::where('min_point', '>', $customer->customerMemberShip->point_history)
            ->orderBy('min_point', 'asc')
            ->first();

        $pointRequired = ($customer->customerMemberShip->point_history / ($membership->max_point - $membership->min_point)) * 100;

        $data = [
            'point'           => $customer->customerMemberShip->point,
            'point_history'   => $customer->customerMemberShip->point_history,
            'next_membership' => $membership->name,
            'next_min_point'  => $membership->min_point,
            'next_max_point'  => $membership->max_point,
            'progress'        => round($pointRequired),
            'remain_point'    => $membership->min_point - $customer->customerMemberShip->point_history
        ];

        return ResponseJson::success($data);
    }

    private function _createDeviceToken($fcmToken, $customer)
    {
        CustomerDeviceToken::updateOrCreate([
            'token' => $fcmToken,
        ], [
            'customer_id' => $customer->id,
        ]);
    }

    public function logout($response = true)
    {
        Auth::user()
            ->currentAccessToken()
            ->delete();

        if ($response) {
            return ResponseJson::success(null, 'Logout successful');
        }
    }

    public function deleteAccount()
    {
        $id = Auth::id();

        $customer = Customer::find($id);

        $customer->update([
            'status' => 'draft',
        ]);

        $this->logout(false);

        return ResponseJson::success(null, 'Account delete successful');
    }
}
