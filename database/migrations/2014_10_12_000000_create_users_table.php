<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('name');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('referral_email')->nullable();
            $table->string('referral_num')->nullable();
            $table->unsignedInteger('user_type');
            $table->boolean('setup')->nullable();
            $table->string('cell_num');
            $table->text('street_number');
            $table->text('barangay');
            $table->text('city_town');
            $table->text('province');
            $table->boolean('inactive')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
}
