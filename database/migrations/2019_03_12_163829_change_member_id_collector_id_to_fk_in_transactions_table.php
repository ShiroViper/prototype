<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMemberIdCollectorIdToFkInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('member_id')->change();
            $table->foreign('member_id')
                ->references('user_id')
                ->on('loan_request')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('collector_id')->change();
            $table->foreign('collector_id')
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
        Schema::table('transactions', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('transactions');
            Schema::enableForeignKeyConstraints();
        });
    }
}
