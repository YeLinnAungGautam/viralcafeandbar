<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Banner;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TaglineSeeder;
use Database\Seeders\TagTableSeeder;
use Database\Seeders\PageTableSeeder;
use Database\Seeders\RoleTableSeeder;
use Database\Seeders\UserTableSeeder;
use Database\Seeders\BannerTableSeeder;
use Database\Seeders\PaymentInfoSeeder;
use Database\Seeders\ProductTableSeeder;
use Database\Seeders\SettingTableSeeder;
use Database\Seeders\CategoryTabelSeeder;
use Database\Seeders\CustomerTableSeeder;
use Database\Seeders\LanguageTableSeeder;
use Database\Seeders\PaymentmethodSeeder;
use Database\Seeders\TemplateTableSeeder;
use Database\Seeders\AttributeTableSeeder;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\PromotionTableSeeder;
use Database\Seeders\MemberShipTableSeeder;
use Database\Seeders\PermissionTableSeeder;
use Database\Seeders\PaymentAccountTableSeeder;
use Database\Seeders\AttributeOptionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            PermissionRoleSeeder::class,
            LanguageTableSeeder::class,
            MemberShipTableSeeder::class,
            AttributeTableSeeder::class,
            AttributeOptionTableSeeder::class,
            CategoryTabelSeeder::class,
            CustomerTableSeeder::class,
            TagTableSeeder::class,
            UserTableSeeder::class,
            PaymentmethodSeeder::class,
            ProductTableSeeder::class,
            PaymentInfoSeeder::class,
            PaymentAccountTableSeeder::class,
            PromotionTableSeeder::class,
            BannerTableSeeder::class,
            PageTableSeeder::class,
            SettingTableSeeder::class,
            TaglineSeeder::class,
            TemplateTableSeeder::class
        ]);
    }
}
