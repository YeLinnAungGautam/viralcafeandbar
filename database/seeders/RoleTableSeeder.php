<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = array(
            [
                'name'       => 'superadmin',
                'guard_name' => 'web',
            ],
            [
                'name'       => 'admin',
                'guard_name' => 'web',
            ],
        );

        Role::insert($roles);
    }
}
