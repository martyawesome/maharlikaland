<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsElectricitySourceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('bills_electricity_source_details', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('bills_electricizty_source_id');
            $table->integer('property_id');
            $table->string('date_covered',30);
            $table->double('consumption');
            $table->double('bill');
            $table->double('payment')->nullable();
            $table->date('date_payment',30)->nullable();
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
        //Schema::drop('bills_electricity_source_details');
    }
}
