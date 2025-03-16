<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Translate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $pages     = [
            [
                'title' => 'Privacy Policy',
                'page_text' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'excerpt' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'title' => 'About Us',
                'page_text' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'excerpt' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'status' => 'active',
                'created_at' => now(),
            ],
        ];
        $translate = [
            [
                'key' => 'title',
                'value' => 'Privacy Policy',
                'language_id' => 1,
                'model_type' => Page::class,
                'model_id' => 1,
                'created_at' => now(),
            ],
            [
                'key' => 'title',
                'value' => 'Privacy Policy',
                'language_id' => 2,
                'model_type' => Page::class,
                'model_id' => 1,
                'created_at' => now(),
            ],
            [
                'key' => 'page_text',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 1,
                'model_type' => Page::class,
                'model_id' => 1,
                'created_at' => now(),
            ],
            [
                'key' => 'page_text',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 2,
                'model_type' => Page::class,
                'model_id' => 1,
                'created_at' => now(),
            ],
            [
                'key' => 'excerpt',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 1,
                'model_type' => Page::class,
                'model_id' => 1,
                'created_at' => now(),
            ],
            [
                'key' => 'excerpt',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 2,
                'model_type' => Page::class,
                'model_id' => 1,
                'created_at' => now(),
            ],
            [
                'key' => 'title',
                'value' => 'About Us',
                'language_id' => 1,
                'model_type' => Page::class,
                'model_id' => 2,
                'created_at' => now(),
            ],
            [
                'key' => 'title',
                'value' => 'About Us',
                'language_id' => 2,
                'model_type' => Page::class,
                'model_id' => 2,
                'created_at' => now(),
            ],
            [
                'key' => 'page_text',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 1,
                'model_type' => Page::class,
                'model_id' => 2,
                'created_at' => now(),
            ],
            [
                'key' => 'page_text',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 2,
                'model_type' => Page::class,
                'model_id' => 2,
                'created_at' => now(),
            ],
            [
                'key' => 'excerpt',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 1,
                'model_type' => Page::class,
                'model_id' => 2,
                'created_at' => now(),
            ],
            [
                'key' => 'excerpt',
                'value' => 'Laboratory and all employees or agents shall not disclose to any third party, except where permitted or required by law, any patient or medical record information regarding the patients requested. The SML Advanced Medical Laboratory and all employees or agents shall comply with',
                'language_id' => 2,
                'model_type' => Page::class,
                'model_id' => 2,
                'created_at' => now(),
            ],

        ];

        Page::insert($pages);
        Translate::insert($translate);
    }
}
