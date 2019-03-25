<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            //  add column these column for add_user_id_and_sched_type_on_schedules_table file to take effect
            // $table->integer('user_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('sched_type');

            $table->date('start_date');
            $table->date('end_date');
            // $table->integer('loan_request_id')->unsigned();
            // $table->boolean('weekly')->default(false);
            // $table->string('color');
            $table->timestamps();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('schedules');
        Schema::enableForeignKeyConstraints();
    }
}
