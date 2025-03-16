<?php

namespace Database\Seeders;

use App\Models\TagLine;
use App\Models\Translate;
use App\Models\TagLineTags;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaglineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $taglines  = [
        //     [
        //         'name'        => 'Buy 10 lakhs / Get 1 Point',
        //         'category_id' => 2,
        //         'product_id'  => null,
        //         'status'      => 'active',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Hello',
        //         'category_id' => null,
        //         'product_id'  => 1,
        //         'status'      => 'active',
        //         'created_at'  => now(),
        //     ],

        // ];
        // $translate = [
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Buy 10 lakhs / Get 1 Point',
        //         'language_id' => 1,
        //         'model_type'  => TagLine::class,
        //         'model_id'    => 1,
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Buy 10 lakhs / Get 1 Point',
        //         'language_id' => 2,
        //         'model_type'  => TagLine::class,
        //         'model_id'    => 1,
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Hello',
        //         'language_id' => 1,
        //         'model_type'  => TagLine::class,
        //         'model_id'    => 2,
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Hello',
        //         'language_id' => 2,
        //         'model_type'  => TagLine::class,
        //         'model_id'    => 2,
        //         'created_at'  => now(),
        //     ],
        // ];


        // Tagline::insert($taglines);
        // Translate::insert($translate);
    }
}
