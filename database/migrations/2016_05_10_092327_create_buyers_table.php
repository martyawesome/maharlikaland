<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('first_name',30);
            $table->string('middle_name',30)->nullable();
            $table->string('last_name',30);
            $table->string('sex',6);
            $table->string('home_address')->nullable();
            $table->string('contact_number_mobile',30)->nullable();
            $table->string('contact_number_home',15)->nullable();
            $table->string('contact_number_office',15)->nullable();
            $table->string('email',50)->nullable();
            $table->string('civil_status',5)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('spouse_name',30)->nullable();
            $table->integer('num_of_children')->nullable();
            $table->string('company_name',50)->nullable();
            $table->string('position',50)->nullable();
            $table->string('company_address',50)->nullable();
            $table->integer('agent_id')->nullable();
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
        Schema::drop('buyers');
    }
}
