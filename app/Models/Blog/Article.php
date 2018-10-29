<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    use SoftDeletes, Sluggable;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'text'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
