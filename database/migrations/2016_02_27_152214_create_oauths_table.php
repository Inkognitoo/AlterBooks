<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider');
            $table->string('oauth_id');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unique(['oauth_id', 'provider']);

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
        Schema::drop('oauths');
    }
}
