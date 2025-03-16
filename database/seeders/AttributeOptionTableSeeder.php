<?php

namespace Database\Seeders;

use App\Models\AttributeOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'attribute_id' => 1,
                'value'        => 'Pink',
            ],
            [
                'attribute_id' => 1,
                'value'        => 'White',
            ],
            [
                'attribute_id' => 2,
                'value'        => 'XL',
            ],
            [
                'attribute_id' => 2,
                'value'        => 'L',
            ],
            [
                'attribute_id' => 2,
                'value'        => 'M',
            ],
            [
                'attribute_id' => 2,
                'value'        => 'S',
            ],
            [
                'attribute_id' => 3,
                'value'        => 'IRON',
            ],
            [
                'attribute_id' => 3,
                'value'        => 'PLATINUM',
            ],
        ];

        AttributeOption::insert($options);

    }
}
