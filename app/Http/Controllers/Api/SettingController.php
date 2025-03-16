<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck("value", "key")->toArray();

        return ResponseJson::success($settings);
    }
}
