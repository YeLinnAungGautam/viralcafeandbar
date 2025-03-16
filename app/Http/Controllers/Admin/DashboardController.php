<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Inertia\Inertia;
use TranslateHelper;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Template;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        [$start, $end] = $this->getDateRange($request);
        $query         = $this->baseQuery($start, $end);

        $dailySalesChart = $this->getDailySalesChart(clone $query, $start, $end);
        // dd($dailySalesChart);
        $topProducts            = $this->getTopProducts(clone $query);
        $topCustomers           = $this->getTopCustomers(clone $query);
        $monthOrderConfirmCount = $this->getMonthOrderConfirmCount(clone $query);
        $monthOrderPendingCount = $this->getMonthOrderPendingCount(clone $query);
        $recentOrders           = $this->recentOrderList(clone $query);

        $productCount  = Product::count();
        $customerCount = Customer::count();

        return Inertia::render("Admin/Dashboard", [
            'monthOrderConfirmCount' => $monthOrderConfirmCount,
            'monthOrderPendingCount' => $monthOrderPendingCount,
            'productCount'           => $productCount,
            'customerCount'          => $customerCount,
            'dailyChart'             => $dailySalesChart,
            'topProducts'            => $topProducts,
            'topCustomers'           => $topCustomers,
            'recentOrders'           => $recentOrders,
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

    private function baseQuery($start, $end)
    {
        return Order::where('orders.deleted_at', null);
    }

    public function recentOrderList($query)
    {
        return $query->with(['orderCustomer'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }

    public function getMonthOrderConfirmCount($query)
    {
        return $query
            ->where('orders.order_status', Order::ORDER_STATUS['confirmed'])
            ->where('orders.payment_status', Order::PAYMENT_STATUS['paid'])
            // ->whereDate('orders.created_at', today())
            ->whereMonth('orders.created_at', now()->month)
            ->count();
    }

    public function getMonthOrderPendingCount($query)
    {
        return $query
            ->where('orders.order_status', Order::ORDER_STATUS['pending'])
            ->where('orders.payment_status', Order::PAYMENT_STATUS['unpaid'])
            // ->whereDate('orders.created_at', today())
            ->whereMonth('orders.created_at', now()->month)
            ->count();
    }

    private function getDailySalesChart($query, $start, $end)
    {
        $dailySales = $query
            ->where('orders.order_status', Order::ORDER_STATUS['confirmed'])
            ->where('orders.payment_status', Order::PAYMENT_STATUS['paid'])
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->select(DB::raw('SUM(total_price) as total'), DB::raw('DATE(orders.created_at) as date'))
            ->whereBetween('orders.created_at', [$start, $end ?: $start])
            ->pluck('total', 'date')
            ->toArray();

        $allDays = [];
        $period  = Carbon::parse($start)->toPeriod($end);
        // Initialize all dates in the period with 0 sales and format them to 'd-M'
        foreach ($period as $date) {
            $allDays[$date->format('d-M')] = 0;
        }

        // Format the dates in the $dailySales array to 'd-M'
        $formattedDailySales = [];
        foreach ($dailySales as $date => $total) {
            $formattedDate                       = Carbon::parse($date)->format('d-M');
            $formattedDailySales[$formattedDate] = $total;
        }

        $fullDailySales = array_replace($allDays, $formattedDailySales);

        $dayLabels = array_map(function ($date) {
            return Carbon::parse($date)->format('d-M');
        }, array_keys($fullDailySales));

        // return (new LarapexChart)->barChart()
        //     ->setTitle('Daily Revenue Overview')
        //     ->addData('Daily Sales', array_values($fullDailySales))
        //     ->setXAxis($dayLabels)
        //     ->setColors(['#00000F'])
        //     ->toVue();
        return [$fullDailySales, $dayLabels];
        // return $dailySales;
    }

    private function getTopProducts($query)
    {
        return $query
            ->where('orders.order_status', Order::ORDER_STATUS['confirmed'])
            ->where('orders.payment_status', Order::PAYMENT_STATUS['paid'])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.product_id', 'products.name', DB::raw('COUNT(order_items.product_id) as product_count'))
            ->groupBy('order_items.product_id', 'products.name')
            ->orderBy('product_count', 'DESC')
            ->limit(10)
            ->get();
    }

    private function getTopCustomers($query)
    {
        return $query
            ->where('orders.order_status', Order::ORDER_STATUS['confirmed'])
            ->where('orders.payment_status', Order::PAYMENT_STATUS['paid'])
            ->with('orderCustomer')
            ->join('order_customers', 'orders.id', '=', 'order_customers.order_id')
            ->select('order_customers.customer_id', DB::raw('SUM(orders.total_price) as total_spent'), 'orders.*')
            ->groupBy('order_customers.customer_id')
            ->orderBy('total_spent', 'DESC')
            ->limit(10)
            ->get();
    }
}
