<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')->on('books');
            $table->integer('chapter_id')->unsigned();
            $table->foreign('chapter_id')
                ->references('id')->on('chapters')
                ->onDelete('cascade');
            $table->integer('number');
            $table->text('text');
            $table->timestamps();
            $table->unique(['book_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
