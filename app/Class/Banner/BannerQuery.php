<?php

namespace App\Class\Banner;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = Banner::latest('id');
    }

    public function withActive()
    {
        $this->query->active();
        return $this;
    }

    public function withSearchByTitle()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('title', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }
    public function withSearchByProduct()
    {
        if ($this->request->input('keyword')) {
            $this->query->orWhereHas('product', function ($query) {
                $query->where('name', 'like', "%{$this->request->keyword}%");
            });
        }
        return $this;
    }
    public function withSearchByCategory()
    {
        if ($this->request->input('keyword')) {
            $this->query->orWhereHas('category', function ($query) {
                $query->where('name', 'like', "%{$this->request->keyword}%");
            });
        }
        return $this;
    }


    public function withCategory()
    {
        $this->query->with('category');
        return $this;
    }

    public function withProduct()
    {
        $this->query->with('product');
        return $this;
    }

    public function withFilterByType()
    {
        if ($this->request->type) {
            $this->query->where('banner_type', $this->request->type);
        }
        return $this;
    }

    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
