<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrderBookingExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // if ($request->input('start_date')) {
        //     $request->validate([
        //         'end_date' => 'required|date|after:start_date',
        //     ]);
        // }
        $results       = [];
        [$start, $end] = $this->getDateRange($request);

        if ($this->_validateRequest($request)) {
            $query   = $this->buildOrderQuery($request, $start, $end);
            $results = $query->paginate(10)->withQueryString();
        }

        return Inertia::render('Admin/Reports/Index', [
            'results'       => $results,
            'customer_name' => $request->customer_name ?? '',
            'order_no'      => $request->order_no ?? '',
        ]);
    }

    private function getDateRange(Request $request)
    {
        if (isset($request->dates)) {
            $start = Carbon::parse($request->dates[0])->startOfDay();
            $end   = ($request->dates[1] != 'Invalid date') ? Carbon::parse($request->dates[1])->endOfDay() : null;
        } else {
            $start = Carbon::now()->subDays(15)->startOfDay();
            $end   = Carbon::now();
        }

        return [$start, $end];
    }

    public function export(Request $request)
    {
        try {
            if ($this->_validateRequest($request)) {
                $fileName = "order_" . date("Y-m-d-H-i-s") . ".xlsx";
                [$start, $end] = $this->getDateRange($request);

                $query   = $this->buildOrderQuery($request, $start, $end);
                $results = $query->get();

                return Excel::download(new OrderBookingExport($results), $fileName);
            }
        } catch (Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    private function buildOrderQuery(Request $request, $start, $end)
    {
        $query = Order::with(['orderCustomer'])
            ->whereNull('orders.deleted_at');

        if (isset($request->customer_name)) {
            $query = $query->whereHas('orderCustomer', function ($c) use ($request) {
                $c->where('order_customers.first_name', 'like', "%{$request->customer_name}%")
                    ->orWhere('order_customers.last_name', 'like', "%{$request->customer_name}%");
            });
        }

        // if (isset($request->start_date) && isset($request->end_date)) {
        //     $startDate = Carbon::parse($request->start_date)->startOfDay();
        //     $endDate   = Carbon::parse($request->end_date)->endOfDay();
        //     $query     = $query->whereBetween('orders.created_at', [$startDate, $endDate]);
        // }

        if (isset($request->dates)) {
            $query = $query->whereBetween('orders.created_at', [$start, $end ?: $start]);
        }

        if (isset($request->order_status)) {
            $query = $query->where('orders.order_status', $request->order_status);
        }

        if (isset($request->payment_status)) {
            $query = $query->where('orders.payment_status', $request->payment_status);
        }

        if (isset($request->order_no)) {
            $query = $query->where('orders.order_no', 'like', "%{$request->order_no}%");
        }

        return $query;
    }

    private function _validateRequest(Request $request)
    {
        return !empty($request->customer_name) || !empty($request->order_no) || !empty($request->dates) || !empty($request->order_status) || !empty($request->payment_status);
    }

    public function reportOrderType(Request $request)
    {
        $query = Order::with(['orderCustomer'])
            ->whereNull('orders.deleted_at');

        if ($request->type == 'daily') {
            $query = $query->whereDate('orders.created_at', today());
        } elseif ($request->type == 'weekly') {
            $query = $query->whereBetween('orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->type == 'monthly') {
            $query = $query->whereBetween('orders.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }
        $results = $query->get();

        $fileName = "order_" . date("Y-m-d-H-i-s") . ".xlsx";
        return Excel::download(new OrderBookingExport($results), $fileName);
    }
}
