<?php

namespace App\Http\Controllers\Admin;

use App\Class\Role\RoleQuery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = new RoleQuery($request);
        $roles = $roles
            ->withPermission()
            ->withSearchByName()
            ->paginate(10);

        return Inertia::render("Admin/Role/Index", [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $permissions = Permission::orderBy('name', 'asc')->get();

        return Inertia::render("Admin/Role/Create", [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::create([
            'name'       => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions);

        return to_route('admin.roles.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $role        = Role::with(['permissions'])->find($id);
        $permissions = Permission::orderBy('name', 'asc')->get();

        return Inertia::render("Admin/Role/Edit", [
            'role'        => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::find($id);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions);

        return to_route('admin.roles.index');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
    }
}
