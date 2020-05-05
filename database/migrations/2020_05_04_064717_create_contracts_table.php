<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('owner_id')->unsigned();
            $table->bigInteger('contract_length_id')->unsigned();
            $table->boolean('paid');
            $table->boolean('valid');
            $table->integer('reminders')->default(0);
            $table->timestamp('start_at');
            $table->timestamp('expires_at');

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('contract_length_id')->references('id')->on('contract_lengths')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
