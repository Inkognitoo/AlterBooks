<?php

namespace App\Providers;

use Auth;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Horizon::auth(function ($request) {
            return (bool) optional(Auth::user())->is_admin && IS_ADMIN_ENVIRONMENT;
        });

       \Validator::extendImplicit('current_password',
           function($attribute, $value, $parameters, $validator)
           {
               return \Hash::check($value, auth()->user()->password);
           });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Отладочная консоль только для окружения отличного от production
        //TODO: использовать только в dev окружении
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        //Русский язык для генерации фейковых данных
        $this->app->singleton(Generator::class, function () {
            return Factory::create('ru_RU');
        });
    }
}
