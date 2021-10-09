<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        if ( env('TYPE_SERVER','develop') == 'staging' || env('TYPE_SERVER','develop') == 'production' ) {
            $domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
            if (strpos($domain, env('APP_DOMAIN')) !== false) {
                \URL::forceSchema('https');
            }
            if (env('APP_ENV') != 'local') {
                $this->app['request']->server->set('HTTPS', 'on');
            }
//        }
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);
    }
}
