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

            $table->string('ru_name')->nullable();
            $table->string('en_name')->nullable();
            $table->string('ru_description')->nullable();
            $table->string('en_description')->nullable();
            $table->string('ru_cover')->nullable();
            $table->string('en_cover')->nullable();

            $table->integer('author_id');
            $table->foreign('author_id')->references('id')->on('users');
            $table->integer('rars_id');
            $table->foreign('rars_id')->references('id')->on('rars');

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
