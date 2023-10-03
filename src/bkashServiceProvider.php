<?php

namespace Mahedi250\Bkash;

use Illuminate\Support\ServiceProvider;
use Mahedi250\Bkash\Payment\Payment;

class bkashServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/config/bkash.php", "bkash");

        $this->app->bind("payment", function () {
            return new Payment();
        });


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/routes/bkashRoutes.php');

          $this->publishes([
            __DIR__ ."/config/bkash.php" => config_path("bkash.php")
        ]);
        // //
    }
}
