<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function generateToken()
    {
        $token = encryptApiToken();
        return ResponseJson::success($token);
    }


    public function validateToken(Request $request)
    {
        $token = $request->token;

        $result = decryptApiToken($token);

        $api_token = config('app.api_token');

        if ($result === $api_token) {
            return ResponseJson::success($token, 'Token still valid.');
        } else {
            return ResponseJson::error('Invalid Token.');
        }
    }

}
