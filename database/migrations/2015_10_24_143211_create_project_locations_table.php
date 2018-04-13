<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('project_locations', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('project_id');
            $table->string('coordinates');
            $table->integer('province_id');
            $table->integer('city_municipality_id');
            $table->string('barangay',30)->nullable();
            $table->string('street',30)->nullable();
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
        //Schema::drop('project_locations');
    }
}
