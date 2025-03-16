<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogController extends Controller
{
    //
    public function index()
    {
        abort_if(Gate::denies('activity_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activityLogs = Activity::with(['causer', 'subject'])->latest('id')->paginate(10);
        return Inertia::render('Admin/ActivityLog/Index', [
            'activityLogs' => $activityLogs
        ]);
    }
}
