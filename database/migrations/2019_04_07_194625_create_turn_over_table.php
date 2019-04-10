<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnOverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turn_over', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('collector_id')->unsigned();
            $table->decimal('amount', 10, 2);
            $table->integer('confirmed')->nullable();
            $table->string('token');
            $table->timestamps();

            $table->foreign('collector_id')
                ->references('id')
                ->on('users')
                ->onupdate('cascade')
                ->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turn_over');
    }
}
