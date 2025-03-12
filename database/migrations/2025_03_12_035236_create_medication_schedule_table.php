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
        Schema::create('medication_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_medication_id')->constrained('patient_medications')->onDelete('cascade');
            $table->integer('interval_hours'); // Example: 12 for every 12 hours
            $table->timestamp('next_dose_time')->nullable(); // Stores next medication time
            $table->string('status');
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
        Schema::dropIfExists('medication_schedule');
    }
};
