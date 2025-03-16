<?php

namespace App\Class\PaymentInfo;

use App\Models\PaymentInfo;
use Illuminate\Http\Request;

class PaymentInfoQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        $this->query = PaymentInfo::latest('id');
    }
    public function withSearchByName()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('name', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }

    public function withAccounts()
    {
        $this->query->with([
            'accounts' => function ($query) {
                $query->active();
            }
        ]);
        return $this;
    }


    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }

    public function limit()
    {
        return $this->query->limit(10);
    }
}
