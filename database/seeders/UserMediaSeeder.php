<?php

namespace Database\Seeders;

use App\Models\Metadata;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserMediaSeeder extends Seeder
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
            'basename' => 'Rodriguez.jpg',
            'filename' => 'Rodriguez.jpg',
            'filepath' => 'backend/assets/images/users/Rodriguez.jpg',
            'type' => 'image/jpeg',
            'size' => '36181',
        ]);
    }
}
