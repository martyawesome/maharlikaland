<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name',50);
            $table->string('slug');
            $table->integer('property_type_id');
            $table->integer('project_id')->nullable();
            $table->integer('joint_venture_id')->nullable();
            $table->integer('model_house_id')->nullable();
            $table->integer('floor_id')->nullable();
            $table->integer('number_of_bedrooms_id')->nullable();
            $table->integer('number_of_bathrooms_id')->nullable();
            $table->double('floor_area')->nullable();
            $table->double('lot_area')->nullable();
            $table->boolean('is_furnished')->nullable();
            $table->integer('parking_availability_id')->nullable();
            $table->double('price')->nullable();
            $table->double('price_per_sqm')->nullable();
            $table->integer('property_status_id');
            $table->integer('agent_id');
            $table->integer('developer_id');
            $table->integer('buyer_id');
            $table->text('main_picture_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('properties');
    }
}
