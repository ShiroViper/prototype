<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLnameFnameMnameUserTypeAddressToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lname')->after('id');
            $table->string('fname')->after('lname');
            $table->string('mname')->after('fname');
            $table->tinyInteger('user_type')->unsigned()->after('mname');
            $table->text('address')->after('user_type');
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
            $table->dropColumn('lname', 'fname', 'mname', 'user_type', 'address');
        });
    }
}
