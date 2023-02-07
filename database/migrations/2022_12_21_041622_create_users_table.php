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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('password');
            $table->bigInteger('mgt_no')->nullable();
            $table->string('department')->nullable();
            $table->string('furigana')->nullable();
            $table->string('family_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('region')->nullable();
            $table->string('official_registration_date')->nullable();
            // New Addition start
            // $table->string('email')->nullable();
            // $table->string('email2')->nullable();            
            // $table->string('birthday')->nullable();
            // $table->string('sex')->nullable();
            // $table->string('postal_code')->nullable();
            // $table->string('current_address')->nullable();
            // $table->string('desired_subject')->nullable();
            // $table->string('preferred_working_style')->nullable();
            // $table->string('current_address_area')->nullable();
            // $table->string('desired_area')->nullable();
            // $table->string('content')->nullable();
            // $table->string('last_updated')->nullable();
            $table->integer('isadmin')->nullable();
            // New Addition end
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
        Schema::dropIfExists('users');
    }
};
