<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_process', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transfer')->nullable();
            $table->integer('request_id');
            $table->integer('admin_id');
            $table->integer('collector_id');
            $table->timestamps();
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
        Schema::dropIfExists('loan_process');
        Schema::enableForeignKeyConstraints();
    }
}
