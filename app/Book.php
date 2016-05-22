<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function rars()
    {
        return $this->belongsTo('App\Rars');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function status()
    {
        return $this->belongsTo('App\BookStatus', 'status_id');
    }
}
