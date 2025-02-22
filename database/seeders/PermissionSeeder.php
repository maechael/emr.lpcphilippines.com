<?php

namespace Database\Seeders;

use App\Models\Permission;
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
        $data = [
            ['id' => 1, 'name' => 'edit_role'],
            ['id' => 2, 'name' => 'create_permission'],
            ['id' => 3, 'name' => 'edit_permission'],
            ['id' => 4, 'name' => 'delete_permission'],
            ['id' => 5, 'name' => 'create_role'],
            ['id' => 6, 'name' => 'delete_role'],
            ['id' => 7, 'name' => 'assign_role'],
            ['id' => 8, 'name' => 'view_import_leads'],
            ['id' => 9, 'name' => 'view_permission'],

        ];
        Permission::insert($data);
    }
}
