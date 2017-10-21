<?php

namespace App\MongoDB;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
    protected $defer = true;

    public function register() {
        $this->app->singleton('mongodb', function($app) {
            $config = $app->make('config');
            $uri = $config->get('services.mongodb.uri');
            $uriOptions = $config->get('services.mongodb.uriOptions');
            $driverOptions =
                $config->get('services.mongodb.driverOptions');
            return new Service($uri, $uriOptions, $driverOptions);
        });
    }

    public function provides() {
        return ['mongodb'];
    }
}