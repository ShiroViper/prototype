<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable(); // both member and collector deactive their account
            $table->unsignedInteger('request_id')->nullable(); // if comment_type is for loan_request
            $table->text('comments');
            $table->string('token'); // this column check for duplicate submitted form
            $table->integer('confirmed')->nullable(); // if this column = 1 means the admin accepted the cancellation of member account
            $table->timestamps();

            $table->foreign('request_id')
                ->references('id')
                ->on('loan_request')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
