<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookTrgmIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        //TODO: разобраться, почему не поднимаются в тестовом окружении
        //DB::statement('CREATE INDEX books_name_trigram ON books USING gist(title gist_trgm_ops);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX IF EXISTS books_name_trigram');
        //DB::statement('DROP EXTENSION IF EXISTS pg_trgm');
    }
}
