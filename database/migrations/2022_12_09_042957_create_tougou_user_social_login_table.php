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
        Schema::create('tougou_user_social_logins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            // $table->string('name')->nullable();
            // $table->string('email')->nullable();
            // $table->date('email_verified_at')->nullable();
            $table->string('line_id')->nullable();
            $table->string('access_tocken')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('socialplus_id')->nullable();
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
        Schema::dropIfExists('user_social_logins');
    }
};
