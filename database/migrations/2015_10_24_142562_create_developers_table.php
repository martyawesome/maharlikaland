<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name');
            $table->string('slug');
            $table->text('overview')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->string('security_code');
            $table->string('address',50)->nullable();
            $table->string('coordinates')->nullable();
            $table->string('email',50)->nullable();
            $table->string('contact_number',50)->nullable();
            $table->string('website_url',50)->nullable();
            $table->string('facebook_url',50)->nullable();
            $table->string('twitter_url',50)->nullable();
            $table->string('linkedin_url',50)->nullable();
            $table->string('logo_path');
            $table->string('header_image_path');
            $table->string('banner_path');
            $table->boolean('is_activated');
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
        Schema::drop('developers');
    }
}
