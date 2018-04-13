<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollAdditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('payroll_additions', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('user_id');
            $table->string('date', 10);
            $table->double('amount');
            $table->integer('payroll_addition_type_id');
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
        //Schema::drop('payroll_additions');
    }
}
