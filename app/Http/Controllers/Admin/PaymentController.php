<?php

namespace App\Http\Controllers\Admin;

use App\Class\Payment\PaymentQuery;
use Inertia\Inertia;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\PaymentResource;
use App\Http\Traits\SaveTranslateTrait;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    use SaveTranslateTrait;
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('paymentmethod_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $payments = new PaymentQuery($request);
        $payments = $payments->withSearchByTitle()->paginate(10);

        return Inertia::render('Admin/PaymentMethod/Index', [
            'payments' => $payments,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('paymentmethod_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render('Admin/PaymentMethod/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'key' => 'required'
        ]);

        $payment = Payment::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'key' => $request->key,
        ]);

        $this->storeTranslate($request, $payment);

        return to_route('admin.payments.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('paymentmethod_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment = Payment::findOrFail($id);
        return Inertia::render('Admin/PaymentMethod/Edit', [
            'payment' => new PaymentResource($payment),
        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies('paymentmethod_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment = Payment::findOrFail($id);
        return Inertia::render('Admin/PaymentMethod/Show', [
            'payment' => new PaymentResource($payment),
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'key' => 'required'
        ]);
        $payment = Payment::findOrFail($id);
        $payment->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'key' => $request->key,
        ]);

        $this->storeTranslate($request, $payment);


        return to_route('admin.payments.edit', $id);
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('paymentmethod_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment = Payment::findOrFail($id);
        $payment->delete();
    }
}
