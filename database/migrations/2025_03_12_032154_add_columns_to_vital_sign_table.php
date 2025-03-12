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
        Schema::table('vital_sign', function (Blueprint $table) {
            $table->string('height')->default(' ');
            $table->string('respiratory_rate')->default(' ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vital_sign', function (Blueprint $table) {
            //
            $table->dropColumn('height');
            $table->dropColumn('respiratory_rate');
        });
    }
};
