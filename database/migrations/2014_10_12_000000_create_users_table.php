<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('password', 60);
            $table->string('email',50)->unique()->nullable();
            $table->string('first_name',30);
            $table->string('middle_name',30)->nullable();
            $table->string('last_name',30);
            $table->string('nickname',30)->nullable();
            $table->string('username',30)->unique()->nullable();
            $table->string('sex',6)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('address',50)->nullable();
            $table->string('contact_number',30)->nullable();
            $table->integer('user_type_id');
            $table->boolean('is_admin_activated');
            $table->boolean('is_mobile_activated');
            $table->boolean('able_to_sell');
            $table->integer('buyer_id');
            $table->integer('agent_id');
            $table->text('profile_picture_path');
            $table->string('facebook_url',50)->nullable();
            $table->string('twitter_url',50)->nullable();
            $table->string('linkdin_url',50)->nullable();
            $table->rememberToken();
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
        //Schema::drop('users');
    }
}
