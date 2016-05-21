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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('reset_code', 60)->nullable();
            $table->boolean('email_verify')->default(false);
            $table->string('email_verify_code', 60);
            $table->string('new_email')->nullable();
            $table->string('email_change_code', 60);

            $table->integer('status_id');
            $table->foreign('status_id')->references('id')->on('user_statuses');

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
