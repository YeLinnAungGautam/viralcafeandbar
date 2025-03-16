<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'key'   => 'app_name',
                'value' => 'Khin Collections',
            ],
            [
                'key'   => 'calculate_price',
                'value' => '1000000',
            ],
            [
                'key'   => 'calculate_txt',
                'value' => 'Buy 10 lakhs / Get 1 Point',
            ],
            [
                'key'   => 'address',
                'value' => 'No.22(B), Pyi Thar Yar Street, Qtr-16, Yankin Township, Yangon, Myanmar',
            ],
            [
                'key'   => 'phone',
                'value' => '+95 9 965 060 665',
            ],
            [
                'key'   => 'email',
                'value' => 'jicktothenick@gmail.com',
            ],
            [
                'key'   => 'facebook',
                'value' => 'facebook.com/KHINCollectionsGems',
            ],
            [
                'key'   => 'google_map',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15519.545340159106!2d100.6083957!3d13.788458649999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29dfb94c1ff2f%3A0x824574094c5af457!2zQWxsIEluIFBsYWNlIOC4peC4suC4lOC4nuC4o-C5ieC4suC4pyA5OOC4reC4nuC4suC4o-C5jOC4l-C5gOC4oeC5ieC4meC5g-C4q-C5ieC5gOC4iuC5iOC4sg!5e1!3m2!1sen!2sth!4v1723192845594!5m2!1sen!2sth" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            ],
            [
                'key'   => 'currency',
                'value' => 'MMK',
            ],
            [
                'key'   => 'thousand_separator',
                'value' => ',',
            ],
            [
                'key'   => 'decimal_separator',
                'value' => '.',
            ],
            [
                'key'   => 'number_decimal',
                'value' => '0',
            ],
        ];

        Setting::insert($data);
    }
}
