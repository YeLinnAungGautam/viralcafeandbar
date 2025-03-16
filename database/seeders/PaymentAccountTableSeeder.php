<?php

namespace Database\Seeders;

use App\Models\PaymentAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'account_name'   => 'Daw Su Su',
                'account_number' => '1234  5678  9012  34567',
                'payment_info_id'        => 1,
            ],
            [
                'account_name'   => 'Daw Su Su',
                'account_number' => '09 123 456 789',
                'payment_info_id'        => 2,
            ],
            [
                'account_name'   => 'Daw Su Su',
                'account_number' => '1234  5678  9012  34567',
                'payment_info_id'        => 3,
            ],
            [
                'account_name'   => 'Daw Su Su',
                'account_number' => '1234  5678  9012  34567',
                'payment_info_id'        => 4,
            ],
        ];

        PaymentAccount::insert($data);

    }
}
