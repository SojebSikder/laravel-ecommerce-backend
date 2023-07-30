<?php

namespace Database\Seeders;

use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Super Admin',
                'name' => 'su-admin',
            ],
            [
                'id'    => 2,
                'title' => 'Admin',
                'name' => 'admin',
            ],
            [
                'id'    => 3,
                'title' => 'Staff',
                'name' => 'staff',
            ],
        ];

        Role::insert($roles);

        // $roles = [
        //     [
        //         'id'    => 1,
        //         'title' => 'Admin',
        //         'name' => 'admin',
        //     ],
        //     [
        //         'id'    => 2,
        //         'title' => 'Tenant Admin',
        //         'name' => 'tenant-admin',
        //     ],
        //     [
        //         'id'    => 3,
        //         'title' => 'Tenant User',
        //         'name' => 'tenant-user',
        //     ],
        // ];

        // Role::insert($roles);
    }
}
