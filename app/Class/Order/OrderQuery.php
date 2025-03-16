<?php

namespace App\Class\Order;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderQuery
{
    /**
     * Create a new class instance.
     */

    private $query;

    public function __construct(public ?Request $request = null)
    {
        $this->query = Order::query();
    }


    public function withOrderCustomer()
    {
        $this->query->with(['orderCustomer']);
        return $this;
    }

    public function withOrderItems()
    {
        $this->query->with(['orderItems']);
        return $this;
    }

    public function withPointTransactions()
    {
        $this->query->with(['pointTransactions']);
        return $this;
    }

    public function withLatestPointTransaction()
    {
        $this->query->with(['lastPointTransaction']);
        return $this;
    }

    public function withPaymentMethod()
    {
        $this->query->with(['payment']);
        return $this;
    }

    public function withFilterByOrderNo()
    {
        if ($this->request->input('order_no')) {
            $this->query->where('orders.order_no', 'like', "%{$this->request->order_no}%");
        }
        return $this;
    }

    public function withFilterByCustomer()
    {
        if ($this->request->input('customer')) {
            $this->query->whereHas('orderCustomer', function ($subQuery) {
                $subQuery->where('order_customers.first_name', 'like', "%{$this->request->customer}%")
                    ->orWhere('order_customers.last_name', 'like', "%{$this->request->customer}%");
            });
        }
        return $this;
    }

    public function withOrderStatus()
    {
        if ($this->request->input('order_status')) {
            $this->query->where('orders.order_status', $this->request->order_status);
        }
        return $this;
    }

    public function withPaymentStatus()
    {
        if ($this->request->input('payment_status')) {
            $this->query->where('orders.payment_status', $this->request->payment_status);
        }
        return $this;
    }

    public function withFilterByDateRange()
    {

        if ($this->request->input('start_date') && $this->request->input('end_date')) {
            $startDate = Carbon::parse($this->request->start_date)->startOfDay();
            $endDate   = Carbon::parse($this->request->end_date)->endOfDay();
            $this->query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        return $this;
    }

    public function withGetByCustomer($id)
    {
        $this->query->where('customer_id', $id);
        return $this;
    }

    public function show($id)
    {
        return $this->query->where('id', $id)->first();
    }

    public function withSorting()
    {
        $sortOrder = $this->request->input('sort_by');
        $column    = $this->request->input('column') ?? 'id';

        if ($sortOrder) {
            $this->query->orderBy($column, $sortOrder);
        }
        return $this;
    }
    public function latest()
    {
        $this->query->latest('orders.id');
        return $this;
    }
    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage);
    }
}
