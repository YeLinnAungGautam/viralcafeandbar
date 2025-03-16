<?php

namespace App\Http\Controllers\Admin;

use App\Class\User\UserQuery;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = new UserQuery($request);

        $users = $users->searchByKey()
            ->withRoles()
            ->paginate(10);


        return Inertia::render('Admin/User/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $userRole = User::with(['roles'])->findOrFail(Auth::id());
        $userRole = $userRole->roles[0]->name;

        if ($userRole == 'superadmin') {

            $roles = Role::get();
        } else {
            $roles = Role::whereNot('name', 'superadmin')->get();
        }



        return Inertia::render('Admin/User/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);


        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $user->assignRole($request->role);
        return to_route('admin.users.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user     = User::with('roles')->findOrFail($id);
        $userRole = User::with(['roles'])->findOrFail(Auth::id());
        $userRole = $userRole->roles[0]->name;

        if ($userRole == 'superadmin') {

            $roles = Role::get();
        } else {
            $roles = Role::whereNot('name', 'superadmin')->get();
        }

        return Inertia::render('Admin/User/Edit', [
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'min:8',
            ]);
            $user->password = $request->password;

            $user->update();
        }

        $user->assignRole($request->role);

        return to_route('admin.users.edit', $id);
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::findOrFail($id);
        $user->delete();
    }
}
