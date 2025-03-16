<?php

namespace App\Class\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        $this->query = Role::latest('id');
    }

    public function withPermission()
    {
        $this->query->with('permissions');
        return $this;
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
