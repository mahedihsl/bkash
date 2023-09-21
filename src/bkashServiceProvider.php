<?php

namespace Mahedi250\Bkash;

use Illuminate\Support\ServiceProvider;

class bkashServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/bkashRoutes.php');
        //
    }
}
