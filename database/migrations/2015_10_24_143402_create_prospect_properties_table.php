<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('prospect_properties', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('property_id');
            $table->integer('agent_id')->nullable();
            $table->integer('prospect_buyer_id');
            $table->string('appointment_slip_path')->nullable();
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
        //Schema::drop('prospect_properties');
    }
}
