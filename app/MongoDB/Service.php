<?php

namespace App\MongoDB;

use MongoDB\Client;

class Service {
    private $mongoDB;

    public function __construct($uri, $uriOptions, $driverOptions) {
        $this->mongoDB = new Client($uri = null, $uriOptions = [],
            $driverOptions = []);
    }

    public function get() {
        return $this->mongoDB;
    }
}