<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchedIdOnLoanRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_request', function (Blueprint $table) {
            $table->unsignedInteger('sched_id')->nullable()->after('paid');
            $table->foreign('sched_id')
                ->references('id')
                ->on('schedules')
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
