<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Jobs\OrderMailJob;
use Illuminate\Http\Request;
use App\Class\Order\OrderQuery;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Api\OrderService;
use App\Class\Product\ProductQuery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Class\Customer\CustomerQuery;
use App\Exports\OrderBookingExport;
use App\Http\Requests\Api\OrderStoreRequest;
use App\Models\Category;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Rabbit;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->input('start_date')) {
            $request->validate([
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
        }

        $orders = new OrderQuery($request);

        $orders = $orders->withOrderCustomer()
            ->withOrderItems()
            ->withFilterByOrderNo()
            ->withFilterByCustomer()
            ->withOrderStatus()
            ->withPaymentStatus()
            ->withFilterByDateRange()
            ->withSorting()
            ->latest()
            ->paginate(10);


        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
        ]);
    }

    public function update($id, Request $request)
    {
        $order = Order::with('payment')->findOrFail($id);
        try {
            $order = $this->orderService->update($request, $order);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['message' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = (new CustomerQuery($request))->withFilterByName()->paginate(1);

        $products = new ProductQuery($request);

        $payments = Payment::active()->get();

        $products = $products
            ->withFilterByName()
            ->withFilterByCategoryId()
            ->withCategories()
            ->withTotalStock()
            ->withSkus()
            ->paginate(12);

        $categories = Category::latest('id')->get();


        return Inertia::render('Admin/Order/Create', [
            'products'        => $products,
            'categories'      => $categories,
            'customers'       => $customers,
            'payment_methods' => $payments,
        ]);
    }

    public function store(OrderStoreRequest $request)
    {
        try {
            // $this->orderService->authId = $request->customer['id'];
            $this->orderService->store($request);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'error' => $th->getMessage(),
            ]);
        }

        return to_route('admin.orders.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::with(['orderCustomer', 'orderItems', 'transactions', 'pointTransactions', 'payment'])->findOrFail($id);

        $methods = Payment::orderBy('key', 'asc')->get();
        return Inertia::render('Admin/Order/Show', [
            'order'   => $order,
            'methods' => $methods,
        ]);
    }

    public function download($id)
    {
        $order = (new OrderQuery())->withOrderItems()->withOrderCustomer()->show($id);

        if (is_null($order)) {
            abort(404);
        }

        $setting = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('pdf.order.invoice', compact('order', 'setting'));

        return $pdf->stream();
    }

    public function resend($id, $name = null)
    {
        $order = (new OrderQuery())
            ->withOrderItems()
            ->withOrderCustomer()
            ->withPaymentMethod()
            ->show($id);

        OrderMailJob::dispatch($order->orderCustomer, $order, $name);

        // if ($order->order_status == 'cancel') {
        // } else {
        //     throw ValidationException::withMessages(['message' => 'You need to change first order status cancel.']);
        // }

    }

    public function destroy($id)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::findOrFail($id);
        $order->delete();
    }
}
