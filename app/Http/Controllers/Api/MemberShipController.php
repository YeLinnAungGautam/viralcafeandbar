<?php

namespace App\Http\Controllers\Api;

use App\Models\MemberShip;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Http\Controllers\Controller;

class MemberShipController extends Controller
{
    //
    public function index()
    {
        $memberships = MemberShip::active()->latest('id')->get();
        return ResponseJson::success($memberships);

    }
    public function show($id)
    {
        $memberShip = MemberShip::active()->find($id);

        if (!$memberShip) {
            return ResponseJson::error('Membership was not found.', 404);
        } else {
            return ResponseJson::success($memberShip);
        }
    }
}
