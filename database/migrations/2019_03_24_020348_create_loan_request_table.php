<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_request', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sched_id')->nullable();
            $table->decimal('loan_amount', 10, 2);
            $table->unsignedInteger('days_payable');
            $table->boolean('confirmed')->nullable();
            $table->integer('get')->nullable();
            $table->integer('paid')->nullable();
            $table->integer('received')->nullable();
            $table->decimal('balance', 10, 2);
            $table->timestamps();
            $table->string('token')->nullable();

            $table->foreign('sched_id')
                ->references('id')
                ->on('schedules')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('loan_request');
        Schema::enableForeignKeyConstraints();
    }
}
