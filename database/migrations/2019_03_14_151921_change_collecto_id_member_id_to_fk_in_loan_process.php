<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCollectoIdMemberIdToFkInLoanProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_process', function (Blueprint $table) {
            
            $table->unsignedInteger('collector_id')->change();
            $table->foreign('collector_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('member_id')->change();
            $table->foreign('member_id')
                ->references('user_id')
                ->on('loan_request')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('admin_id')->change();
            $table->foreign('admin_id')
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
        Schema::table('loan_process', function (Blueprint $table) {
            // Schema::disableForeignKeys();
            // Schema::dropIfExists('loan_process');
            // Schema::enableForeignKeys();
        });
    }
}
