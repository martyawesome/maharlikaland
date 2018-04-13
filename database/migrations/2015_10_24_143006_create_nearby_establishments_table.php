<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNearbyEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('nearby_establishments', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('project_id');
            $table->string('nearby_establishment',50);
            $table->string('slug');
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
        //Schema::drop('nearby_establishments');
    }
}
