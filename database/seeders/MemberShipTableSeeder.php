<?php

namespace Database\Seeders;

use App\Models\MemberShip;
use Illuminate\Database\Seeder;

class MemberShipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memberships = [
            [
                'name'       => 'Silver',
                'rule'       => 'Lorem',
                'min_point'  => 10,
                'max_point'  => 50,
                'percentage' => 2,
                'created_at' => now(),
            ],
            [
                'name'       => 'Gold',
                'rule'       => 'Lorem',
                'min_point'  => 51,
                'max_point'  => 200,
                'percentage' => 3,
                'created_at' => now(),
            ],
            [
                'name'       => 'Diamond',
                'rule'       => 'Lorem',
                'min_point'  => 201,
                'max_point'  => 30000,
                'percentage' => 5,
                'created_at' => now(),
            ],
        ];

        MemberShip::insert($memberships);

    }
}
