<?php

namespace Database\Seeders;

use App\Models\PermissionRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['permission_id' => 1, 'role_id' => 5],
            ['permission_id' => 2, 'role_id' => 5],
            ['permission_id' => 3, 'role_id' => 5],
            ['permission_id' => 4, 'role_id' => 5],
            ['permission_id' => 5, 'role_id' => 5],
            ['permission_id' => 6, 'role_id' => 5],
            ['permission_id' => 7, 'role_id' => 5],
            ['permission_id' => 8, 'role_id' => 5],
            ['permission_id' => 9, 'role_id' => 5],

        ];
        PermissionRole::insert($data);
    }
}
