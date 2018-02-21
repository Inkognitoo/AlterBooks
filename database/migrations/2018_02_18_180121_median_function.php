<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MedianFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION _final_median(anyarray) RETURNS float8 AS $$ 
                                  WITH q AS
                                  (
                                     SELECT val
                                     FROM unnest($1) val
                                     WHERE VAL IS NOT NULL
                                     ORDER BY 1
                                  ),
                                  cnt AS
                                  (
                                    SELECT COUNT(*) AS c FROM q
                                  )
                                  SELECT AVG(val)::float8
                                  FROM 
                                  (
                                    SELECT val FROM q
                                    LIMIT  2 - MOD((SELECT c FROM cnt), 2)
                                    OFFSET GREATEST(CEIL((SELECT c FROM cnt) / 2.0) - 1,0)  
                                  ) q2;
                                $$ LANGUAGE SQL IMMUTABLE;
                                 
                                ");

        DB::statement(" DROP AGGREGATE IF EXISTS median(anyelement)");

        DB::statement("CREATE AGGREGATE median(anyelement) (
                                  SFUNC=array_append,
                                  STYPE=anyarray,
                                  FINALFUNC=_final_median,
                                  INITCOND='{}'
                                );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(" DROP AGGREGATE median(anyelement)");

        DB::statement("DROP FUNCTION _final_median(anyarray)");
    }
}
