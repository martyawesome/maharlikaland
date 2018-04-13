<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('journals', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('date', 10);
            $table->integer('journal_type_id');
            $table->integer('user_id');
            $table->text('entry');
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
        //Schema::drop('journals');
    }
}
