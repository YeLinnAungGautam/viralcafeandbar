<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerService
{

    public function store(Request $request)
    {
        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => $request->username,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'status'     => $request->status,
            'password'   => Hash::make($request->password),
        ]);

        $this->addMemberShip($customer);

        return $customer;
    }

    public function update(Customer $customer, Request $request)
    {

        DB::beginTransaction();
        try {
            $customer->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'username'   => $request->username,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'status'     => $request->status ?? 'active',
            ]);

            if ($request->password) {
                // $request->validate([
                //     'password' => 'min:8',
                // ]);
                $customer->password = Hash::make($request->password);
                $customer->update();
            }

            if ($request->hasFile('image')) {
                $file     = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();

                Storage::disk(config('filesystems.default'))->put("public/profile/$filename", file_get_contents($file->getPathname()));

                $customer->image = $filename;
                $customer->update();
            }

            DB::commit();

            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function addMemberShip(Customer $customer)
    {
        $customer->customerMemberShip()->create();
    }
}
