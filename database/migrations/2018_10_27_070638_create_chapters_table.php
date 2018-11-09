<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
            $table->integer('number');
            $table->string('name');
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
        Schema::dropIfExists('chapters');
    }
}
