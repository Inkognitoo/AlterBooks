<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Book;

class MakeSlugNotNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Book::withoutGlobalScopes()->get() as $book) {
            $book->save();
        }

        Schema::table('books', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });
    }
}
