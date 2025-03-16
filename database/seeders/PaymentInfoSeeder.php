<?php

namespace Database\Seeders;

use App\Models\PaymentInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'KBZ',
            ],
            [
                'name' => 'KBZ Pay',
            ],
            [
                'name' => 'AYA',
            ],
            [
                'name' => 'CB',
            ],
        ];

        PaymentInfo::insert($data);

    }
}
