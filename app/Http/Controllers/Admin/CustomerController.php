<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Api\CustomerResource;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\PointTransaction;
use App\Services\CustomerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Class\Customer\CustomerQuery;
use App\Http\Traits\SaveCustomerTrait;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isEmpty;

class CustomerController extends Controller
{
    use SaveCustomerTrait;


    public function __construct(public CustomerService $customerService) {}

    public function index(Request $request)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = new CustomerQuery($request);
        $customers = $customers
            ->withFilterByName()
            ->withFilterByUsername()
            ->withFilterByPhone()
            ->withFilterByEmail()
            ->paginate(10);


        return Inertia::render("Admin/Customer/Index", [
            'customers' => $customers,
        ]);
    }
    //
    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Inertia::render("Admin/Customer/Create");
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'nullable|unique:customers,username',
            'email'    => 'required_without:phone|nullable|email|unique:customers,email',
            'phone'    => 'required_without:email|nullable|numeric|unique:customers,phone',
            'password' => 'required|min:8',
        ]);

        $data = $this->customerService->store($request);
        $this->saveMeta($request->meta, $data);


        return to_route('admin.customers.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = Customer::with('customerMeta')->findOrFail($id);
        $customer = new CustomerResource($customer);

        return Inertia::render("Admin/Customer/Edit", [
            'customer' => $customer,
        ]);
    }
    public function show($id)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = Customer::with('customerMeta')->findOrFail($id);

        $customer           = new CustomerResource($customer);
        $orders             = Order::with(['orderCustomer', 'orderItems', 'transactions',])->where('customer_id', $id)->paginate(5);
        $point_transactions = PointTransaction::with(['customer'])->where('customer_id', $id)->paginate(5);
        return Inertia::render("Admin/Customer/Show", [
            'customer'           => $customer,
            'orders'             => $orders,
            'point_transactions' => $point_transactions,
        ]);
    }

    //update
    public function update($id, Request $request)
    {

        $request->validate([
            'username' => 'nullable|unique:customers,username,' . $id,
            'email'    => 'required_without:phone|nullable|email|unique:customers,email,' . $id,
            'phone'    => 'required_without:email|nullable|numeric|unique:customers,phone,' . $id,
        ]);

        $customer = Customer::findOrFail($id);

        $this->customerService->update($customer, $request);
        $this->saveMeta($request->meta, $customer);



        return to_route('admin.customers.edit', $id);
    }
    //delete
    public function destroy($id)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = Customer::findOrFail($id);
        $customer->delete();
    }
}
