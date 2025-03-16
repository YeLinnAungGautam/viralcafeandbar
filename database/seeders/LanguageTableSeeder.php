<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $result = [
            [
                'name'       => 'English',
                'code'       => 'en',
                'is_default' => 1,
            ],
            [
                'name'       => 'Burmese',
                'code'       => 'my',
                'is_default' => 0,
            ],
        ];

        Language::insert($result);

    }
}
