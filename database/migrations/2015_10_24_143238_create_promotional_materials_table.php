<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionalMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('promotional_materials', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('project_id');
            $table->string('file_path');
            $table->integer('media_type_id');
            $table->string('extension');
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
        //Schema::drop('promotional_materials');
    }
}
