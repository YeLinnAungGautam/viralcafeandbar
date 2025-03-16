<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\TranslateCollection;
use App\Models\Localization;
use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function index()
    {
        $local = Localization::with('language')->get()
            ->groupBy('language.code')
            ->map(function ($query) {
                return $query->pluck('value', 'key');
            });

        return ResponseJson::success($local);
    }
}
