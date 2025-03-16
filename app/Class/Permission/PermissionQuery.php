<?php

namespace App\Class\Permission;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = Permission::latest('id');
    }
    public function withSearchByName()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('name', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }


    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
