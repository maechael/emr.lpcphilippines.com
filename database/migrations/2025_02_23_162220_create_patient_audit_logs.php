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
        Schema::create('patient_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patient_profile');
            $table->foreignId('user_profile_id')->constrained('user_profiles');
            $table->longText('changes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_audit_logs');
    }
};
