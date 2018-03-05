<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name',30);
            $table->string('slug');
            $table->integer('project_type_id');
            $table->text('overview')->nullable();
            $table->date('opening_date')->nullable();
            $table->date('development_date')->nullable();
            $table->boolean('is_preselling');
            $table->integer('agent_id');
            $table->integer('developer_id');
            $table->string('logo_path');
            $table->string('banner_path');
            $table->boolean('is_active');
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
        Schema::drop('projects');
    }
}
