<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertTablesToInnoDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'users',
            'password_resets',
            'failed_jobs',
            'user_details',
            'user_types',
            'genders',
            'locations',
            'cities',
            'tickets'
        ];

        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = InnoDB');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'users',
            'password_resets',
            'failed_jobs',
            'user_details',
            'user_types',
            'genders',
            'locations',
            'cities',
            'tickets'
        ];
        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = MyISAM');
        }
    }
}
