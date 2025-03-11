<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_profile', function (Blueprint $table) {
            //
            $table->foreignId('user_profile_id')->nullable()->constrained('user_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_profile', function (Blueprint $table) {
            //
            $table->dropColumn('user_profile_id');
        });
    }
};
