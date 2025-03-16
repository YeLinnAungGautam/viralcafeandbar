<?php

namespace Database\Seeders;

use App\Http\Requests\SeedProductRequest;
use App\Models\Product;
use App\Models\Translate;
use Illuminate\Database\Seeder;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\Sku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Http\Request;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //
        // $products   = [
        //     [
        //         'name'        => 'Rose quartz,Ruby & Diopside Rings',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-pearl-earrings',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Sapphire & Diamond Rings',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-perl-bracelet',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Diamond,Ruby & Diopside Pendant',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-pearl-earrings',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Spinal Earings',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-perl-bracelet',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Amethyst Earings',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-pearl-earrings',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'BT,Pink Sapphire,Citrine Earing',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-perl-bracelet',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Lemon Quartz , Yellow & Pink Sapphire Pendant',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-pearl-earrings',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'Colorful Garnet Earings',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-perl-bracelet',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => '2 Way Heart Shape Amethyst & Pearl Set',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-pearl-earrings',
        //         'created_at'  => now(),
        //     ],
        //     [
        //         'name'        => 'BT,Pink Sapphire,Citrine Earing',
        //         'type'        => 'simple',
        //         'description' => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'active'      => 'active',
        //         'slug'        => 'corrine-perl-bracelet',
        //         'created_at'  => now(),
        //     ],
        // ];
        // $skus       =
        //     [
        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "1200000",
        //             "sale_price"          => "100000",
        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => null,
        //             "discount"            => null,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 1,
        //         ],

        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "2000000",
        //             "sale_price"          => "1500000",
        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => null,
        //             "discount"            => null,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 2,
        //         ],
        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "4500000",
        //             "sale_price"          => "4000000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 3,
        //         ],

        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "5000000",
        //             "sale_price"          => "4500000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 4,
        //         ],
        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "4000000",
        //             "sale_price"          => "3600000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 5,
        //         ],

        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "4500000",
        //             "sale_price"          => "4000000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 6,
        //         ],
        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "3000000",
        //             "sale_price"          => "2500000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 7,
        //         ],

        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "5000000",
        //             "sale_price"          => "4500000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 8,
        //         ],
        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "4500000",
        //             "sale_price"          => "4000000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 9,
        //         ],

        //         [
        //             "code"                => "Sequi omnis quibusda",
        //             "original_price"      => "4500000",
        //             "sale_price"          => "4000000",

        //             "stock"               => "34",
        //             "stock_status"        => "in_stock",
        //             "weight"              => "53",
        //             "discount_type"       => 'percent',
        //             "discount"            => 2,
        //             "discount_schedule"   => false,
        //             "discount_start_date" => null,
        //             "discount_end_date"   => null,
        //             'product_id'          => 10,
        //         ],


        //     ];
        // $categories = [
        //     [
        //         'product_id'  => 1,
        //         'category_id' => 1,
        //     ],
        //     [
        //         'product_id'  => 2,
        //         'category_id' => 2,
        //     ],
        //     [
        //         'product_id'  => 3,
        //         'category_id' => 1,
        //     ],
        //     [
        //         'product_id'  => 4,
        //         'category_id' => 1,
        //     ],
        //     [
        //         'product_id'  => 5,
        //         'category_id' => 1,
        //     ],
        //     [
        //         'product_id'  => 6,
        //         'category_id' => 3,
        //     ],
        //     [
        //         'product_id'  => 7,
        //         'category_id' => 1,
        //     ],
        //     [
        //         'product_id'  => 8,
        //         'category_id' => 4,
        //     ],
        //     [
        //         'product_id'  => 9,
        //         'category_id' => 3,
        //     ],
        //     [
        //         'product_id'  => 10,
        //         'category_id' => 3,
        //     ],
        // ];
        // $translate  = [
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Rose quartz,Ruby & Diopside Rings',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 1,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 1,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 1,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 1,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Sapphire & Diamond Rings',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 2,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 2,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 2,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 2,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Diamond,Ruby & Diopside Pendant',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 3,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 3,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 3,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 3,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Spinal Earings',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 4,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 4,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 4,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 4,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Amethyst Earings',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 5,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 5,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 5,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 5,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'BT,Pink Sapphire,Citrine Earing',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 6,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 6,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 6,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 6,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Lemon Quartz , Yellow & Pink Sapphire Pendant',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 7,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 7,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 7,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 7,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'Colorful Garnet Earings',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 8,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 8,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 8,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 8,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => '2 Way Heart Shape Amethyst & Pearl Set',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 9,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 9,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 9,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 9,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'BT,Pink Sapphire,Citrine Earing',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 10,
        //     ],
        //     [
        //         'key'         => 'name',
        //         'value'       => 'ပစ္စည်း',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 10,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'Pearls are definitely having a moment right now. Add an understated and elegant touch to your everyday look with the Sapphire & Diamond Rings with a brushed gold-tone finish. Featuring a string of small faux pearls, evenly spaced on a delicate chain link, this is a charming everyday piece that will brighten up your day and mood. A teardrop-shaped faux pearl hangs at the end of the chain, creating movement and visual interest.',
        //         'language_id' => 1,
        //         'model_type'  => Product::class,
        //         'model_id'    => 10,
        //     ],
        //     [
        //         'key'         => 'description',
        //         'value'       => 'အကြောင်းရာ',
        //         'language_id' => 2,
        //         'model_type'  => Product::class,
        //         'model_id'    => 10,
        //     ],

        // ];

        // Product::insert($products);
        // Sku::insert($skus);
        // ProductCategory::insert($categories);
        // Translate::insert($translate);
    }
}
