<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPerMonthAmountPerMonthDateToLoanRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_request', function (Blueprint $table) {
            $table->decimal('per_month_amount', 10, 2)->nullable()->after('balance');
            $table->string('per_month_from')->nullable()->after('per_month_amount');
            $table->string('per_month_to')->nullable()->after('per_month_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('loan_request', function (Blueprint $table) {
        //     $table->dropcolumn('per_month_amount');
        //     $table->dropcolumn('per_month_from');
        //     $table->dropcolumn('per_month_to');
        // });
    }
}
