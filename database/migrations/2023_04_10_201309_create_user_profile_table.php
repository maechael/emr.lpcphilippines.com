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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 255);
            $table->string('lastname', 255);
            $table->string('id_num', 255);
            $table->string('position');
            $table->boolean('is_active')->default(false);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('media_id')->constrained('metadata');
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
        Schema::dropIfExists('user_profiles');
    }
};
