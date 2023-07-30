<?php

namespace Database\Seeders;

use App\Models\User\Permission;
use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i                = 0;
        $permissions      = [];
        $permissionGroups = [
            'user_management', 'role_management', 'order_management', 'coupon_management',
            'product_management', 'category_management', 'manufacturer_management',
            'page_management', 'setting_management',
        ];

        foreach ($permissionGroups as $permissionGroup) {
            foreach (['read', 'create', 'edit', 'show', 'delete'] as $permission) {
                $permissions[] = [
                    'id'    => ++$i,
                    'title' => $permissionGroup . '_' . $permission,
                ];
            }
        }

        Permission::insert($permissions);
    }
}
