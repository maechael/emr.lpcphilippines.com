<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        UserProfile::create([
            'user_id' => 1,
            'firstname' => 'Joseph',
            'lastname' => 'Bayot',
            'id_num' => '001',
            'is_active' => 1,
            'media_id' => 1
        ]);
    }
}
