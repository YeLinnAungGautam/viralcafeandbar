<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\PromotionTag;
use App\Models\Translate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $promotions = [
        //     [
        //         'name' => 'promotion best offer',
        //         'slug' => 'promotion-best-offer',
        //         'category_id' => 2,
        //         'product_id' => null,
        //         'status' => 'active',
        //         'description' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'created_at' => now(),
        //     ],
        //     [
        //         'name' => 'big sale',
        //         'slug' => 'big-sale',
        //         'category_id' => null,
        //         'product_id' => 1,
        //         'status' => 'active',
        //         'description' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'created_at' => now(),
        //     ],
        //     [
        //         'name' => 'sale',
        //         'slug' => 'sale',
        //         'category_id' => null,
        //         'product_id' => 4,
        //         'status' => 'active',
        //         'description' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'created_at' => now(),
        //     ],
        // ];

        // $promotion_tags = [
        //     [
        //         'promotion_id' => 1,
        //         'tag_id' => 1,

        //     ],
        //     [
        //         'promotion_id' => 1,
        //         'tag_id' => 2,

        //     ],
        //     [
        //         'promotion_id' => 1,
        //         'tag_id' => 3,

        //     ],

        //     [
        //         'promotion_id' => 2,
        //         'tag_id' => 1,

        //     ],
        //     [
        //         'promotion_id' => 2,
        //         'tag_id' => 2,

        //     ],
        //     [
        //         'promotion_id' => 2,
        //         'tag_id' => 3,

        //     ],
        //     [
        //         'promotion_id' => 3,
        //         'tag_id' => 1,

        //     ],
        //     [
        //         'promotion_id' => 3,
        //         'tag_id' => 2,

        //     ],
        //     [
        //         'promotion_id' => 3,
        //         'tag_id' => 3,

        //     ],
        // ];

        // $translate = [
        //     [
        //         'key' => 'name',
        //         'value' => 'promotion best offer',
        //         'language_id' => 1,
        //         'model_type' => Promotion::class,
        //         'model_id' => 1,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'name',
        //         'value' => 'promotion best offer',
        //         'language_id' => 2,
        //         'model_type' => Promotion::class,
        //         'model_id' => 1,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'description',
        //         'value' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'language_id' => 1,
        //         'model_type' => Promotion::class,
        //         'model_id' => 1,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'description',
        //         'value' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'language_id' => 2,
        //         'model_type' => Promotion::class,
        //         'model_id' => 1,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'name',
        //         'value' => 'big sale',
        //         'language_id' => 1,
        //         'model_type' => Promotion::class,
        //         'model_id' => 2,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'name',
        //         'value' => 'big sale',
        //         'language_id' => 2,
        //         'model_type' => Promotion::class,
        //         'model_id' => 2,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'description',
        //         'value' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'language_id' => 1,
        //         'model_type' => Promotion::class,
        //         'model_id' => 2,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'description',
        //         'value' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'language_id' => 2,
        //         'model_type' => Promotion::class,
        //         'model_id' => 2,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'name',
        //         'value' => 'sale',
        //         'language_id' => 1,
        //         'model_type' => Promotion::class,
        //         'model_id' => 3,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'name',
        //         'value' => 'sale',
        //         'language_id' => 2,
        //         'model_type' => Promotion::class,
        //         'model_id' => 3,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'description',
        //         'value' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'language_id' => 1,
        //         'model_type' => Promotion::class,
        //         'model_id' => 3,
        //         'created_at' => now(),
        //     ],
        //     [
        //         'key' => 'description',
        //         'value' => 'A sales promotion is a marketing strategy where a business will use short-term campaigns to spark interest and create demand for a product, service or other offers. Sales promotions can have many objectives and ideal outcome',
        //         'language_id' => 2,
        //         'model_type' => Promotion::class,
        //         'model_id' => 3,
        //         'created_at' => now(),
        //     ],



        // ];

        // Promotion::insert($promotions);
        // PromotionTag::insert($promotion_tags);
        // Translate::insert($translate);
    }
}
