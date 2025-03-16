<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name'       => 'Color',
                'created_at' => now(),
            ],
            [
                'name'       => 'Size',
                'created_at' => now(),
            ],
            [
                'name'       => 'Material',
                'created_at' => now(),
            ],
        ];

        Attribute::insert($attributes);

    }
}
