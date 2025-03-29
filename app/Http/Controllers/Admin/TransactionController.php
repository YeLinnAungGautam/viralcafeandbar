<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\MediaUploadingTrait;
use App\Jobs\OrderMailJob;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\Api\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    use MediaUploadingTrait;

    public function store(Request $request)
    {
        $order = Order::with(['transactions', 'orderCustomer'])->find($request->order_id);

        $calculate = $order->total_price - $order->transactions->sum('amount');

        $request->validate([
            'payment_date'   => 'required',
            'amount'         => 'required|numeric|gt:0|lte:' . $calculate,
            'payment_method' => 'required|exists:payments,id',
            'order_id'       => 'required',
            'customer_id'    => 'nullable',
            'upload'         => 'required_if:payment_method,1',
        ], [
            'upload.required_if' => 'The upload field is required when payment method is bank transfer.',
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'order_id'       => $request->order_id,
                'customer_id'    => $request->customer_id,
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'note'           => $request->noted,
                'payment_date'   => Carbon::parse($request->payment_date)->format('Y-m-d'),
            ]);

            $order->load('transactions');

            if ($order->total_price === $order->transactions->sum('amount')) {

                $request->merge([
                    'order_status' => 'confirmed',
                ]);

                (new OrderService)->update($request, $order);
            }

            if ($request->input('upload')) {
                foreach ($request->input('upload') as $file) {
                    $transaction
                        ->addMedia(storage_path('tmp/uploads/' . basename($file)))
                        ->toMediaCollection('images');
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::with(['transactions'])->find($request->order_id);

        $transaction = Transaction::findOrFail($id);

        $calculate = $order->total_price - $order->transactions->sum('amount') + $transaction->amount;

        $request->validate([
            'payment_date'   => 'required',
            'amount'         => 'required|numeric|gt:0|lte:' . $calculate,
            'payment_method' => 'required|exists:payments,id',
            'order_id'       => 'required',
            'customer_id'    => 'nullable',
            'upload'         => 'required_if:payment_method,1',
        ], [
            'upload.required_if' => 'The upload field is required when payment method is bank transfer.',
        ]);

        DB::beginTransaction();
        try {

            $transaction->update([
                'order_id'       => $request->order_id,
                'customer_id'    => $request->customer_id,
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'note'           => $request->noted,
                'payment_date'   => Carbon::parse($request->payment_date)->format('Y-m-d'),
            ]);

            if ($request->input('upload')) {
                $file = $request->input('upload');

                if (Storage::disk('tmp')->exists('uploads/' . $file[0])) {

                    $transaction->addMedia(storage_path('tmp/uploads/' . basename($file[0])))->toMediaCollection('images');
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
    }
}
