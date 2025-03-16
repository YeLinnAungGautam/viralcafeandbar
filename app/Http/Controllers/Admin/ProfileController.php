<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    //
    public function show($id)
    {

        $profile = User::findOrFail(Auth::id());

        return Inertia::render('Admin/Profile/Show', [
            'profile' => $profile,
        ]);
    }
    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'old_password' => 'nullable|required_with:new_password'
        ]);

        $profile = User::findOrFail(Auth::id());



        if ($request->old_password) {
            $request->validate([
                'new_password' => 'required|min:8',
            ]);
            if (!Hash::check($request->old_password, $profile->password)) {
                throw ValidationException::withMessages([
                    'old_password' => ['It does not match your old password.'],
                ]);
            }
            $profile->password = $request->new_password;
            $profile->update();
            Auth::setUser($profile);
            session()->regenerate();
        }
        $profile->update([
            'name' => $request->name,
        ]);
        return to_route('admin.profiles.show', Auth::id());
    }
}
