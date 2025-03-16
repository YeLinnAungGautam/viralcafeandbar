<?php

namespace App\Http\Controllers\Admin;

use App\Class\MemberShip\MemberShipQuery;
use Inertia\Inertia;
use App\Models\MemberShip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class MemberShipController extends Controller
{
    //
    public function index(Request $request)
    {
        abort_if(Gate::denies('membership_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $memberships = new MemberShipQuery($request);
        $memberships = $memberships->withSearchByName()->paginate(10);

        return Inertia::render("Admin/Membership/Index", [
            'memberships' => $memberships,
        ]);
    }

    //create
    public function create()
    {
        abort_if(Gate::denies('membership_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Inertia::render("Admin/Membership/Create");
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'rule' => 'required',
            'min_point' => 'required|numeric',
            'max_point' => 'required|numeric',
            'percentage' => 'required|numeric',
        ]);

        MemberShip::create([
            'name' => $request->name,
            'rule' => $request->rule,
            'min_point' => $request->min_point,
            'max_point' => $request->max_point,
            'percentage' => $request->percentage,
            'status' => $request->status,
        ]);

        return to_route('admin.memberships.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('membership_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $membership = MemberShip::findOrFail($id);
        return Inertia::render("Admin/Membership/Edit", [
            'membership' => $membership,
        ]);
    }
    public function show($id)
    {
        abort_if(Gate::denies('membership_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $membership = MemberShip::findOrFail($id);
        return Inertia::render('Admin/Membership/Show', [
            'membership' => $membership,
        ]);
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'rule' => 'required',
            'min_point' => 'required|numeric',
            'max_point' => 'required|numeric',
            'percentage' => 'required|numeric',
        ]);

        $membership = MemberShip::findOrFail($id);

        $membership->update([
            'name' => $request->name,
            'rule' => $request->rule,
            'min_point' => $request->min_point,
            'max_point' => $request->max_point,
            'percentage' => $request->percentage,
            'status' => $request->status,
        ]);

        return to_route('admin.memberships.edit', $id);
    }

    //delete
    public function destroy($id)
    {
        abort_if(Gate::denies('membership_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $membership = MemberShip::findOrFail($id);
        $membership->delete();
    }
}
