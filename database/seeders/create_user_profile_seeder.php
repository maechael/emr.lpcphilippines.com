<?php

namespace Database\Seeders;

use App\Models\Metadata;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class create_user_profile_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Metadata::create([
            'id' => 1,
            'basename' => 'Elchico(256x256).png',
            'filename' => 'Elchico(256x256).png',
            'filepath' => 'backend/assets/images/users/Elchico(256x256).png',
            'type' => 'png',
            'size' => '850kb',
        ]);

        UserProfile::create([
            'id' => 1,
            'firstname' => 'Maechael',
            'lastname' => 'Elchico',
            'id_num' => 001,
            'position' => 'Head Staff',
            'is_active' => 1,
            'user_id' => 1,
            'media_id' => 1
        ]);
    }
}
