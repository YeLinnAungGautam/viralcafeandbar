<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderBookingExport implements FromView
{
    protected $index = 0;

    public function __construct(public $orders)
    {
    }

    public function view(): View
    {
        return view('exports.orders', [
            'orders' => $this->orders,
        ]);
    }
}
