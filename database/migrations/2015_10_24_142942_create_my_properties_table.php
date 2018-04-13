<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('my_properties', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('property_id');
            $table->double('unpaid_penalty');
            $table->double('remaining_balance');
            $table->double('total_payment');
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
        //Schema::drop('my_properties');
    }
}
