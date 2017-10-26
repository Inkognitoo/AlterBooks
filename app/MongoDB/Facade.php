<?php

namespace App\MongoDB;

class Facade extends \Illuminate\Support\Facades\Facade {
    protected static function getFacadeAccessor() {
        return 'mongodb';
    }
}