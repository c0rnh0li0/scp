<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractLengthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_lengths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('duration');
            $table->double('price');
            $table->bigInteger('valute_id')->unsigned();
            $table->foreign('valute_id')->references('id')->on('valutes')->onDelete('cascade');
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
        Schema::dropIfExists('contract_lengths');
    }
}
