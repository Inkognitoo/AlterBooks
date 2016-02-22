<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function author()
    {
        return $this->belongsTo('App\User', 'author_id', 'user_id');
    }

    public function rars()
    {
        return $this->belongsTo('App\Rars');
    }
}
