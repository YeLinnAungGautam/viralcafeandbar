<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerMemberShip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $result = [
            [
                'first_name' => "Hein Htet",
                'last_name' => 'Aung',
                'username' => 'heinhtet',
                'email' => "hh1716101@gmail.com",
                'phone' => "09446202832",
                'password' => Hash::make('password'),
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'first_name' => "Nyi waing",
                'last_name' => 'Chit',
                'username' => 'nyichit',
                'email' => "nyiwaingchit5@gmail.com",
                'phone' => "09808137851",
                'password' => Hash::make('password'),
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'first_name' => "Zwe",
                'last_name' => 'Zwe',
                'username' => 'zwe',
                'email' => "zwe@gmail.com",
                'phone' => "09424627268",
                'password' => Hash::make('password'),
                'status' => 'active',
                'created_at' => now(),
            ],
        ];

        $customermemberships = [
            [
                'point_history' => 0,
                'point' => 0,
                'customer_id' => 1,
            ],
            [
                'point_history' => 0,
                'point' => 0,
                'customer_id' => 2,
            ],
            [
                'point_history' => 0,
                'point' => 0,
                'customer_id' => 3,
            ],

        ];

        Customer::insert($result);

        CustomerMemberShip::insert($customermemberships);
    }
}
