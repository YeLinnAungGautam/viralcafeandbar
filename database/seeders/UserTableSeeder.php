<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            [
                'name'     => 'Super Admin',
                'email'    => 'ztun.25@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Admin',
                'email'    => 'admin@admin.com',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'HeinHtet',
                'email'    => 'hein@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Nyichit',
                'email'    => 'Nyi@gmail.com',
                'password' => Hash::make('password'),
            ],
        );

        User::create($users[0])->assignRole('superadmin');
        User::create($users[1])->assignRole('admin');
        User::create($users[2])->assignRole('admin');
        User::create($users[3])->assignRole('admin');
    }
}
