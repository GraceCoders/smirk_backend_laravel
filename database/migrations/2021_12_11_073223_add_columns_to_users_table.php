<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->nullable();
            $table->string('otp')->nullable();
            $table->string('full_name')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('is_verified')->nullable();
            $table->text('about')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('laugh_id')->nullable();
            $table->string('matching_id')->nullable();
            $table->string('show_id')->nullable();
            $table->string('age_range')->nullable();
            $table->string('max_distance')->nullable();
            $table->string('ethnicity_preference')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('0->male/1->female');
            $table->tinyInteger('user_type')->nullable()->comment('0>user,1->admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}