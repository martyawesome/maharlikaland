<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashAdvancePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('cash_advance_payments', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('user_id');
            $table->string('date', 10);
            $table->double('amount');
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
        //Schema::drop('cash_advance_payments');
    }
}
