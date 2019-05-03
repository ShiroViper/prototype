<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFaceFrontIdBackIdPhotoToMemberRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member__requests', function (Blueprint $table) {
            $table->string('face_photo')->nullable();
            $table->string('front_id_photo')->nullable();
            $table->string('back_id_photo')->nullable();
            $table->string('id_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member__requests', function (Blueprint $table) {
            //
        });
    }
}
