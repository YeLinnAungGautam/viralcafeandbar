<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Translate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentmethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentmethods = [
            [
                'title'       => 'Direct bank transfer',
                'key'         => 'bank_transfer',
                'description' => 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.',
                'status'      => 'active',
            ],
            [
                'title'       => 'Cash on arrival',
                'key'         => 'cash_on_arrival',
                'description' => 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.',
                'status'      => 'active',
            ],
        ];

        $translate = [
            [
                'key'         => 'title',
                'value'       => 'Cash on arrival',
                'language_id' => 1,
                'model_type'  => Payment::class,
                'model_id'    => 2,
            ],
            [
                'key'         => 'title',
                'value'       => 'အိမ်ရောက်ငွေချေစနစ်',
                'language_id' => 2,
                'model_type'  => Payment::class,
                'model_id'    => 2,
            ],
            [
                'key'         => 'description',
                'value'       => 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.',
                'language_id' => 1,
                'model_type'  => Payment::class,
                'model_id'    => 2,
            ],
            [
                'key'         => 'description',
                'value'       => 'အကြောင်းရာများ',
                'language_id' => 2,
                'model_type'  => Payment::class,
                'model_id'    => 2,
            ],
            [
                'key'         => 'title',
                'value'       => 'Direct bank transfer',
                'language_id' => 1,
                'model_type'  => Payment::class,
                'model_id'    => 1,
            ],
            [
                'key'         => 'title',
                'value'       => 'ဘဏ်ငွေလွဲ',
                'language_id' => 2,
                'model_type'  => Payment::class,
                'model_id'    => 1,
            ],
            [
                'key'         => 'description',
                'value'       => 'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.',
                'language_id' => 1,
                'model_type'  => Payment::class,
                'model_id'    => 1,
            ],
            [
                'key'         => 'description',
                'value'       => 'အကြောင်းရာများ',
                'language_id' => 2,
                'model_type'  => Payment::class,
                'model_id'    => 1,
            ],
        ];
        Payment::insert($paymentmethods);
        Translate::insert($translate);
    }
}
