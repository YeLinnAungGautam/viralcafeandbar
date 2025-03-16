<?php

namespace App\Http\Controllers\Admin;

use App\Class\Permission\PermissionQuery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = new PermissionQuery($request);
        $permissions = $permissions->withSearchByName()->paginate(10);

        return Inertia::render("Admin/Permission/Index", [
            'permissions' => $permissions,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Inertia::render("Admin/Permission/Create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return to_route('admin.permissions.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $permission = Permission::find($id);

        return Inertia::render("Admin/Permission/Edit", [
            'permission' => $permission,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $permissions = Permission::find($id);
        $permissions->update([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return to_route('admin.permissions.edit', $id);
    }

    public function destroy($id)
    {
        $role = Permission::find($id);
        $role->delete();
    }
}
