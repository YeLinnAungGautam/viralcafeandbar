<?php

namespace App\Http\Controllers\Api;

use App\Class\Order\OrderItemValidate;
use App\Class\Order\OrderQuery;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Api\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        $orders = new OrderQuery($request);

        $orders = $orders->withOrderItems()
            ->withOrderCustomer()
            ->withPointTransactions()
            ->withPaymentMethod()
            ->withSorting()
            ->latest()
            ->withGetByCustomer(Auth::id())
            ->paginate($request->limit ?? 10);

        return response()->json([
            'total'          => $orders->total(),
            'current_page'   => $orders->currentPage(),
            'per_page'       => $orders->perPage(),
            'data'           => OrderResource::collection($orders->items()),
            'has_more_pages' => $orders->hasMorePages(),
        ]);
    }

    public function show($id)
    {
        $order = new OrderQuery();

        $order = $order
            ->withOrderItems()
            ->withOrderCustomer()
            ->withLatestPointTransaction()
            ->withGetByCustomer(Auth::id())
            ->show(id: $id);

        if (is_null($order)) {
            return ResponseJson::error('Your order was not found.', 404);
        } else {
            return ResponseJson::success(new OrderResource($order));
        }
    }

    public function store(OrderStoreRequest $request)
    {
        try {
            $order = $this->orderService->store($request);
            return ResponseJson::success($order, 'Thank you for your order!');
        } catch (Exception $e) {
            return ResponseJson::error($e->getMessage(), 404);
        }
    }

    public function validateItem(Request $request)
    {
        // try {
        //     (new OrderItemValidate)->validateItem($request);
        //     return ResponseJson::success($request->all());
        // } catch (Exception $e) {
        //     return ResponseJson::error($e->getMessage());
        // }
    }
}
