<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    //
    public function index()
    {
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings  = Setting::pluck('value', 'key')->toArray();
        $templates = Template::all();
        return Inertia::render('Admin/Setting/Index', [
            'settings'  => $settings,
            'templates' => $templates,
        ]);
    }

    public function store(Request $request)
    {
        $request = $request->all();

        foreach ($request as $key => $value) {
            if (is_file($value)) {
                $saveImage = $value->store('images', 'public');
                $value     = asset("storage/" . $saveImage);
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
