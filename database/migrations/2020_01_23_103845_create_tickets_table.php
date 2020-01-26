<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');

            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('place_id')->unsigned()->index();
            $table->bigInteger('modified_by')->nullable()->unsigned();
            $table->bigInteger('deleted_by')->nullable()->unsigned();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('tickets', function (Blueprint $table) {


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
