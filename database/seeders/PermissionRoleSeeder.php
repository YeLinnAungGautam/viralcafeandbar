<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For super admin
        $superadmin  = Role::findOrFail(config('constants.role_id.super_admin'));
        $permissions = Permission::all();
        $superadmin->givePermissionTo($permissions);

        // For admin
        $admin       = Role::findOrFail(config('constants.role_id.admin'));
        $permissions = $permissions->filter(function ($permission) {
            return substr($permission->name, 0, 5) != 'role_' &&
                substr($permission->name, 0, 13) != 'localization_' &&
                substr($permission->name, 0, 11) != 'membership_' &&
                substr($permission->name, 0, 11) != 'permission_' &&
                substr($permission->name, 0, 8) != 'control_' &&
                substr($permission->name, 0, 9) != 'tag_line_' &&
                substr($permission->name, 0, 12) != 'translation_' &&
                substr($permission->name, 0, 7) != 'payment' &&
                substr($permission->name, 0, 7) != 'banner_' &&
                substr($permission->name, 0, 10) != 'promotion_' &&
                substr($permission->name, 0, 10) != 'marketing_' &&
                substr($permission->name, 0, 4) != 'tag_' &&
                substr($permission->name, 0, 9) != 'template_' &&
                substr($permission->name, 0, 5) != 'page_' &&
                substr($permission->name, 0, 9) != 'language_';

        });

        $admin->givePermissionTo($permissions);
    }
}
