<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('property_locations', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('property_id');
            $table->string('coordinates');
            $table->integer('province_id');
            $table->integer('city_municipality_id');
            $table->string('barangay',30)->nullable();
            $table->string('street',30)->nullable();
            $table->string('block_number',10)->nullable();
            $table->string('lot_number',10)->nullable();
            $table->integer('unit_number')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('property_locations');
    }
}
