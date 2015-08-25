<?php

namespace App\Providers;

use App\Repositories\MrtRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;


class MrtServiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Classes\IMrtCrawler','App\Classes\TaipeiMrtCrawler');
    }

}
