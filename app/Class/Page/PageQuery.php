<?php

namespace App\Class\Page;

use App\Models\Page;
use Illuminate\Http\Request;

class PageQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = Page::latest('id');
    }
    public function withSearchByTitle()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('title', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }


    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
