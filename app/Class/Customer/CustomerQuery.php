<?php

namespace App\Class\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = Customer::latest('id');
    }

    public function withFilterByName()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('first_name', 'like', "%{$this->request->keyword}%")
                ->orWhere("last_name", "like", "%{$this->request->keyword}%");
        }
        return $this;
    }

    public function withFilterByUsername()
    {
        if ($this->request->input('keyword')) {
            $this->query->orWhere('username', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }


    public function withFilterByPhone()
    {
        if ($this->request->input('keyword')) {
            $this->query->orWhere('customers.phone', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }


    public function withFilterByEmail()
    {
        if ($this->request->input('keyword')) {
            $this->query->orWhere('customers.email', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }


    public function withMetaKey()
    {
        $this->query->with('customerMeta');
        return $this;
    }

    public function withFilterByAll()
    {
        if ($this->request->input('keyword')) {
            $this->query
                ->where('username', 'like', "%{$this->request->keyword}%")
                ->orWhere("email", "like", "%{$this->request->keyword}%")
                ->orWhere("phone", "like", "%{$this->request->keyword}%")
                ->orWhere('first_name', 'like', "%{$this->request->keyword}%")
                ->orWhere("last_name", "like", "%{$this->request->keyword}%");
        }
        return $this;
    }

    public function getProfile($id)
    {
        $customer = Customer::with([
            'customerMeta',
            'customerMemberShip' => function ($query) {
                $query->join('member_ships', 'customer_member_ships.member_ship_id', '=', 'member_ships.id')
                    ->select(
                        'customer_member_ships.*',
                        'member_ships.id',
                        'member_ships.name',
                        'member_ships.min_point',
                        'member_ships.max_point',
                        DB::raw('
                        IF(customer_member_ships.is_percentage_default = 1, customer_member_ships.percentage, member_ships.percentage) as percentage
                      '),
                    );
            }
        ])
            ->select('customers.*')
            ->find($id);

        return $customer;
    }

    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
