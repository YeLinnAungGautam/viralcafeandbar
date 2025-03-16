<?php

namespace App\Class\Promotion;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = Promotion::latest('id');
    }
    public function withSearchByName()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('name', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }

    public function withTags()
    {
        $this->query->with('tags');
        return $this;
    }


    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
