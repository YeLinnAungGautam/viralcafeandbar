<?php

namespace App\Class\PaymentAccount;

use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PaymentAccountQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = PaymentAccount::latest('id');
    }
    public function withSearchByName()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('account_name', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }
    public function withBanks()
    {
        $this->query->with('banks');
        return $this;
    }


    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
