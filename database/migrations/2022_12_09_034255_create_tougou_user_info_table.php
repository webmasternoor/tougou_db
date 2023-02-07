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
        Schema::create('tougou_user_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('lastname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname_kana')->nullable();
            $table->string('firstname_kana')->nullable();
            $table->date('birthdate');
            $table->string('zip')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();

            $table->string('college_type')->nullable();
            $table->string('college_name')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('medical_license_year')->nullable();
            $table->string('medical_license_month')->nullable();
            $table->string('medical_license_day')->nullable();
            $table->string('desired_working_j')->nullable();
            $table->string('desired_working_h')->nullable();
            $table->string('desired_working_s')->nullable();
            $table->string('desired_working_k')->nullable();
            
            $table->string('j_location1')->nullable();
            $table->string('j_location2')->nullable();
            $table->string('j_location3')->nullable();
            $table->string('h_location1')->nullable();
            $table->string('h_location2')->nullable();
            $table->string('h_location3')->nullable();
            $table->string('s_location1')->nullable();
            $table->string('s_location2')->nullable();
            $table->string('s_location3')->nullable();
            $table->string('k_location1')->nullable();
            $table->string('k_location2')->nullable();
            $table->string('k_location3')->nullable();

            $table->string('j_subject', 255)->nullable();
            $table->string('j_subject_others', 255)->nullable();
            $table->string('h_subject', 255)->nullable();
            $table->string('h_subject_others', 255)->nullable();
            $table->string('s_subject', 255)->nullable();
            $table->string('s_subject_others', 255)->nullable();
            
            $table->string('kiboukamoku')->nullable();
            
            $table->string('doctor_id')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('sex')->nullable();
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('tougou_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
};
