<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVicinityMapsProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('vicinity_maps_projects', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('project_id');
            $table->string('image_path');
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
        //Schema::drop('vicinity_maps_projects');
    }
}
