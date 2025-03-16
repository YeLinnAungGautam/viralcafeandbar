<?php

namespace App\Class\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuery
{
    /**
     * Create a new class instance.
     */
    private $query;
    public function __construct(public ?Request $request = null)
    {
        //
        $this->query = User::latest('id');
    }
    public function searchByKey()
    {
        if ($this->request->input('keyword')) {
            $this->query->where('name', 'like', "%{$this->request->keyword}%");
        }
        return $this;
    }

    public function withRoles()
    {
        $this->query->with('roles')->whereNot('id', Auth::id())->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'superadmin');
        });

        return $this;
    }


    public function paginate($perPage = 10)
    {
        return $this->query->paginate($perPage)->withQueryString();
    }
}
