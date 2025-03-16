<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointTransactionController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::id();

        $customer = Customer::find($id);

        $points = $customer->pointTransactions()->with('order')->latest('id')->paginate($request->limit ?? 10);

        return response()->json([
            'total'          => $points->total(),
            'current_page'   => $points->currentPage(),
            'per_page'       => $points->perPage(),
            'data'           => $points->items(),
            'has_more_pages' => $points->hasMorePages(),
        ]);
    }
}
