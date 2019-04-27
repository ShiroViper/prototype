<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdPhotoIdFrontIdBackToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('face_photo')->nullable();
            $table->string('front_id_photo')->nullable();
            $table->string('back_id_photo')->nullable();
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
