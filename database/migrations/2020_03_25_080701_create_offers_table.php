<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('short_description', 255);
            $table->longText('long_description');
            $table->string('promo_image')->nullable();
            $table->decimal('real_price', 6, 2);
            $table->decimal('offered_price', 6, 2);
            $table->boolean('include_global')->default(false);
            $table->boolean('featured')->default(false);
            $table->longText('notes')->nullable();
            $table->bigInteger('owner_id')->nullable()->unsigned();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('modified_by')->nullable()->unsigned();
            $table->bigInteger('deleted_by')->nullable()->unsigned();
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('offers');
    }
}
