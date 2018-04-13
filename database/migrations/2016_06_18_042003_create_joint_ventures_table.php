<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJointVenturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('joint_ventures', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('project_id');
            $table->string('name',50);
            $table->string('slug',50);
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
        //Schema::drop('joint_ventures');
    }
}
