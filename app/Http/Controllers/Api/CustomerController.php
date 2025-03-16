<?php

namespace App\Http\Controllers\Api;

use App\Class\Customer\CustomerQuery;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function filterByKeyword(Request $request)
    {
        $customers = new CustomerQuery($request);

        $customers = $customers->withMetaKey()
            ->withFilterByName()
            ->withFilterByUsername()
            ->withFilterByPhone()
            ->withFilterByEmail()
            ->paginate(10);
            
        $customers = CustomerResource::collection($customers);

        return response()->json([
            'total'          => $customers->total(),
            'current_page'   => $customers->currentPage(),
            'per_page'       => $customers->perPage(),
            'data'           => $customers->items(),
            'has_more_pages' => $customers->hasMorePages(),
        ]);
    }
}
