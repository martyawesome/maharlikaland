<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsWaterSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('bills_water_source', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('project_id');
            $table->string('date_covered',30);
            $table->double('consumption');
            $table->double('bill');
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
        //Schema::drop('bills_water_source');
    }
}
