<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_id');
            $table->string('name');
            $table->string('description');
            $table->string('cover')->nullable();

            $table->integer('author_id');
            $table->foreign('author_id')->references('id')->on('users');
            $table->integer('rars_id');
            $table->foreign('rars_id')->references('id')->on('rars');
            $table->integer('status_id');
            $table->foreign('status_id')->references('id')->on('book_statuses');
            $table->integer('language_id');
            $table->foreign('language_id')->references('id')->on('languages');

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
        Schema::drop('books');
    }
}
