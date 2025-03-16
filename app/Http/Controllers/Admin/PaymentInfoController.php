<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\PaymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Class\PaymentInfo\PaymentInfoQuery;
use Symfony\Component\HttpFoundation\Response;

class PaymentInfoController extends Controller
{
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('paymentinfo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentinfos = new PaymentInfoQuery($request);
        $paymentinfos = $paymentinfos->withAccounts()
            ->withSearchByName()
            ->paginate(10);

        return Inertia::render('Admin/PaymentInfo/Index', [
            'paymentinfos' => $paymentinfos,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('paymentinfo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/PaymentInfo/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            $paymentinfo = PaymentInfo::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            if ($request->input('upload')) {
                $files = $request->input('upload');

                if (Storage::disk('tmp')->exists('uploads/' . $files[0])) {
                    $paymentinfo->addMedia(storage_path('tmp/uploads/' . basename($files[0])))->toMediaCollection('images');
                }
            }

            DB::commit();

            return to_route('admin.payment-infos.index');
        } catch (Exception $e) { {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('paymentinfo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentinfo = PaymentInfo::with([
            'accounts' => function ($query) {
                $query->active();
            }
        ])->findOrFail($id);
        return Inertia::render('Admin/PaymentInfo/Edit', [
            'paymentinfo' => $paymentinfo,
        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies('paymentinfo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentinfo = PaymentInfo::with([
            'accounts' => function ($query) {
                $query->active();
            }
        ])->findOrFail($id);
        return Inertia::render('Admin/PaymentInfo/Show', [
            'paymentinfo' => $paymentinfo,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $paymentinfo = PaymentInfo::findOrFail($id);

            $paymentinfo->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            if ($request->input('upload')) {
                $files = $request->input('upload');

                if (Storage::disk('tmp')->exists('uploads/' . $files[0])) {
                    $paymentinfo->addMedia(storage_path('tmp/uploads/' . basename($files[0])))->toMediaCollection('images');
                }
            }

            DB::commit();

            return to_route('admin.payment-infos.edit', $id);
        } catch (Exception $e) { {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('paymentinfo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentinfo = PaymentInfo::findOrFail($id);
        $paymentinfo->delete();
    }
}
