<?php

namespace Database\Seeders;

use App\Models\PatientProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientProfileFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PatientProfile::factory()->count(500)->create();
    }
}
