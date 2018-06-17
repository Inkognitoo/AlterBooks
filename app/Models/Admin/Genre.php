<?php

namespace App\Models\Admin;

use App\Models\Genre as BaseGenre;
use App\Traits\Admin\Attributes;

/**
 * Class Genre
 *
 * @package App\Models\Admin
 */
class Genre extends BaseGenre
{
    use Attributes;
}