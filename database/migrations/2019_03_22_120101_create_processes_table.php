<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transfer')->nullable();
            $table->unsignedInteger('request_id');
            $table->foreign('request_id')
                ->references('id')
                ->on('loan_request')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedInteger('admin_id');
            $table->foreign('admin_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedInteger('collector_id');
            $table->foreign('collector_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('process_type')->nullable();
            $table->timestamps();
            
            // $table->unsignedInteger('collector_id');
            // $table->unsignedInteger('request_id');
            // $table->unsignedInteger('admin_id');
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
        Schema::dropIfExists('processes');
        Schema::enableForeignKeyConstraints();
    }
}
