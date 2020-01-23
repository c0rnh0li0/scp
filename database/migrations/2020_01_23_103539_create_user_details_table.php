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

            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('user_type_id')->unsigned()->index();
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('cascade');

            $table->integer('gender_id')->unsigned()->index();
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('cascade');

            $table->integer('location_id')->nullable()->unsigned()->index();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');

            $table->timestamps();
            $table->integer('modified_by')->nullable()->unsigned()->index();
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('deleted_by')->nullable()->unsigned()->index();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
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
