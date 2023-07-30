<?php

namespace Database\Seeders;

use App\Models\User\Permission;
use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_permissions = Permission::all();

        // $suadmin_permissions = $all_permissions->filter(function ($permission) {
        //     return substr($permission->title, 0, 18) == 'tenant_management_';
        // });
        Role::findOrFail(1)->permissions()->sync($all_permissions->pluck('id'));


        $admin_permissions = $all_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 16) == 'user_management_'
                || substr($permission->title, 0, 17) == 'order_management_'
                || substr($permission->title, 0, 18) == 'coupon_management_'
                || substr($permission->title, 0, 19) == 'product_management_'
                || substr($permission->title, 0, 20) == 'category_management_'
                || substr($permission->title, 0, 24) == 'manufacturer_management_'
                || substr($permission->title, 0, 16) == 'page_management_'
                || substr($permission->title, 0, 19) == 'setting_management_';
        });
        Role::findOrFail(2)->permissions()->sync($admin_permissions);


        $staff_permissions = $all_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 17) == 'order_management_'
                || substr($permission->title, 0, 18) == 'coupon_management_'
                || substr($permission->title, 0, 19) == 'product_management_'
                || substr($permission->title, 0, 20) == 'category_management_'
                || substr($permission->title, 0, 24) == 'manufacturer_management_'
                || substr($permission->title, 0, 16) == 'page_management_';
        });
        Role::findOrFail(3)->permissions()->sync($staff_permissions);
    }
}
