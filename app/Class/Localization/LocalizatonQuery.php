<?php

namespace App\Class\Localization;

use App\Models\Localization;
use Illuminate\Http\Request;

class LocalizatonQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = Localization::query();
    }

    public function withLanguage()
    {
        $this->query->with('language');
        return $this;
    }
    public function withSorting()
    {
        $sortOrder = $this->request->input('sortBy');
        $column    = $this->request->input('column') ?? 'id';

        if ($sortOrder) {
            $this->query->orderBy($column, $sortOrder);
        }

        return $this;
    }

    public function withFilterByKey()
    {
        $this->query->where('key', 'like', "%{$this->request->keyword}%");
        return $this;
    }

    public function withFilterByValue()
    {
        $this->query->orWhere('value', 'like', "%{$this->request->keyword}%");
        return $this;
    }

    public function latest()
    {
        $this->query->latest('id');
        return $this;
    }


    public function get()
    {
        return $this->query->get();
    }
}
