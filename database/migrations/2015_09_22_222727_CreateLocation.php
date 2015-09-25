<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('level', ['continent', 'country', 'province', 'city', 'suburb']);
            $table->integer('parent_id')->unsigned();
            $table->string('path');
            $table->double('longitude');
            $table->double('latitude');
            $table->timestamps();

            $table->index('level');
            $table->index('path');
            $table->index(['longitude', 'latitude']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('locations');
    }
}
