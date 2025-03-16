<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\PaymentInfo;
use Illuminate\Http\Request;
use App\Models\PaymentAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Class\PaymentAccount\PaymentAccountQuery;

class PaymentAccountController extends Controller
{
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('paymentaccount_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentaccounts = new PaymentAccountQuery($request);
        $paymentaccounts = $paymentaccounts->withSearchByName()
            ->withBanks()
            ->paginate(10);

        return Inertia::render('Admin/PaymentAccount/Index', [
            'paymentaccounts' => $paymentaccounts,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('paymentaccount_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banks = PaymentInfo::active()->get();
        return Inertia::render('Admin/PaymentAccount/Create', [
            'banks' => $banks,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name'    => 'required|string',
            'account_number'  => 'required',
            'payment_info_id' => 'required',
        ], [
            'payment_info_id.required' => 'The bank field is required.',
        ]);

        PaymentAccount::create([
            'account_name'    => $request->account_name,
            'account_number'  => $request->account_number,
            'payment_info_id' => $request->payment_info_id,
            'status'          => $request->status,
        ]);

        return to_route('admin.payment-accounts.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('paymentaccount_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentaccount = PaymentAccount::with(['banks'])->findOrFail($id);
        $banks          = PaymentInfo::active()->get();

        return Inertia::render('Admin/PaymentAccount/Edit', [
            'paymentaccount' => $paymentaccount,
            'banks'          => $banks,
        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies('paymentaccount_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentaccount = PaymentAccount::with(['banks'])->findOrFail($id);

        return Inertia::render('Admin/PaymentAccount/Show', [
            'paymentaccount' => $paymentaccount,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'account_name'    => 'required|string',
            'account_number'  => 'required',
            'payment_info_id' => 'required',
        ], [
            'payment_info_id.required' => 'The bank field is required.',
        ]);

        $paymentaccount = PaymentAccount::findOrFail($id);

        $paymentaccount->update([
            'account_name'    => $request->account_name,
            'account_number'  => $request->account_number,
            'payment_info_id' => $request->payment_info_id,
            'status'          => $request->status,
        ]);

        return to_route('admin.payment-accounts.edit', $id);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('paymentaccount_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentaccount = PaymentAccount::findOrFail($id);

        $paymentaccount->delete();
    }
}
