<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'user_access',
            ],
            [
                'name' => 'user_create',
            ],
            [
                'name' => 'user_edit',
            ],
            [
                'name' => 'user_delete',
            ],
            [
                'name' => 'user_show',
            ],
            [
                'name' => 'customer_access',
            ],
            [
                'name' => 'customer_create',
            ],
            [
                'name' => 'customer_edit',
            ],
            [
                'name' => 'customer_delete',
            ],
            [
                'name' => 'customer_show',
            ],
            [
                'name' => 'membership_access',
            ],
            [
                'name' => 'membership_create',
            ],
            [
                'name' => 'membership_edit',
            ],
            [
                'name' => 'membership_delete',
            ],
            [
                'name' => 'membership_show',
            ],
            [
                'name' => 'role_access',
            ],
            [
                'name' => 'role_create',
            ],
            [
                'name' => 'role_edit',
            ],
            [
                'name' => 'role_show',
            ],
            [
                'name' => 'role_delete',
            ],
            [
                'name' => 'permission_access',
            ],
            [
                'name' => 'permission_create',
            ],
            [
                'name' => 'permission_edit',
            ],
            [
                'name' => 'permission_show',
            ],
            [
                'name' => 'permission_delete',
            ],
            [
                'name' => 'dashboard_access',
            ],
            [
                'name' => 'product_access',
            ],
            [
                'name' => 'product_create',
            ],
            [
                'name' => 'product_edit',
            ],
            [
                'name' => 'product_show',
            ],
            [
                'name' => 'product_delete',
            ],
            [
                'name' => 'category_access',
            ],
            [
                'name' => 'category_create',
            ],
            [
                'name' => 'category_edit',
            ],
            [
                'name' => 'category_show',
            ],
            [
                'name' => 'category_delete',
            ],
            [
                'name' => 'language_access',
            ],
            [
                'name' => 'language_create',
            ],
            [
                'name' => 'language_edit',
            ],
            [
                'name' => 'language_show',
            ],
            [
                'name' => 'language_delete',
            ],
            [
                'name' => 'tag_access',
            ],
            [
                'name' => 'tag_create',
            ],
            [
                'name' => 'tag_edit',
            ],
            [
                'name' => 'tag_show',
            ],
            [
                'name' => 'tag_delete',
            ],
            [
                'name' => 'promotion_access',
            ],
            [
                'name' => 'promotion_create',
            ],
            [
                'name' => 'promotion_edit',
            ],
            [
                'name' => 'promotion_show',
            ],
            [
                'name' => 'promotion_delete',
            ],
            [
                'name' => 'localization_access',
            ],
            [
                'name' => 'localization_create',
            ],
            [
                'name' => 'localization_edit',
            ],
            [
                'name' => 'localization_show',
            ],
            [
                'name' => 'localization_delete',
            ],
            [
                'name' => 'page_access',
            ],
            [
                'name' => 'page_create',
            ],
            [
                'name' => 'page_edit',
            ],
            [
                'name' => 'page_show',
            ],
            [
                'name' => 'page_delete',
            ],
            [
                'name' => 'banner_access',
            ],
            [
                'name' => 'banner_create',
            ],
            [
                'name' => 'banner_edit',
            ],
            [
                'name' => 'banner_show',
            ],
            [
                'name' => 'banner_delete',
            ],
            [
                'name' => 'paymentmethod_access',
            ],
            [
                'name' => 'paymentmethod_create',
            ],
            [
                'name' => 'paymentmethod_edit',
            ],
            [
                'name' => 'paymentmethod_show',
            ],
            [
                'name' => 'paymentmethod_delete',
            ],
            [
                'name' => 'paymentinfo_access',
            ],
            [
                'name' => 'paymentinfo_create',
            ],
            [
                'name' => 'paymentinfo_edit',
            ],
            [
                'name' => 'paymentinfo_show',
            ],
            [
                'name' => 'paymentinfo_delete',
            ],
            [
                'name' => 'paymentaccount_access',
            ],
            [
                'name' => 'paymentaccount_create',
            ],
            [
                'name' => 'paymentaccount_edit',
            ],
            [
                'name' => 'paymentaccount_show',
            ],
            [
                'name' => 'paymentaccount_delete',
            ],
            [
                'name' => 'order_access',
            ],
            [
                'name' => 'order_create',
            ],
            [
                'name' => 'order_edit',
            ],
            [
                'name' => 'order_show',
            ],
            [
                'name' => 'order_delete',
            ],
            [
                'name' => 'tag_line_access',
            ],
            [
                'name' => 'tag_line_create',
            ],
            [
                'name' => 'tag_line_edit',
            ],
            [
                'name' => 'tag_line_show',
            ],
            [
                'name' => 'tag_line_delete',
            ],
            [
                'name' => 'template_access',
            ],
            [
                'name' => 'template_create',
            ],
            [
                'name' => 'template_edit',
            ],
            [
                'name' => 'template_show',
            ],
            [
                'name' => 'template_delete',
            ],
            [
                'name' => 'control_access',
            ],
            [
                'name' => 'payment_management_access',
            ],
            [
                'name' => 'translation_access',
            ],
            [
                'name' => 'setting_access',
            ],
            [
                'name' => 'transaction_create',
            ],
            [
                'name' => 'transaction_edit',
            ],
            [
                'name' => 'transaction_delete',
            ],
            [
                'name' => 'transaction_access',
            ],
            [
                'name' => 'transaction_show',
            ],
            [
                'name' => 'activity_log_access',
            ],
            [
                'name' => 'setting_management',
            ],
            [
                'name' => 'marketing_management',
            ],
        ];

        $data = [];

        foreach ($permissions as $name) {
            $data[] = [
                'name'       => $name['name'],
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Permission::insert($data);
    }
}
