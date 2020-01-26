<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone')->nullable();
            $table->longText('description')->nullable();
            $table->string('picture')->nullable();

            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('user_type_id')->nullable()->unsigned();
            $table->bigInteger('gender_id')->nullable()->unsigned();
            $table->bigInteger('location_id')->nullable()->unsigned();
            $table->bigInteger('modified_by')->nullable()->unsigned();
            $table->bigInteger('deleted_by')->nullable()->unsigned();

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
        Schema::dropIfExists('user_details');
    }
}
