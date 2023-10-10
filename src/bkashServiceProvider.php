<?php

namespace Mahedi250\Bkash;

use Illuminate\Support\ServiceProvider;
use Mahedi250\Bkash\Products\CheckoutUrl;
use Mahedi250\Bkash\app\Exceptions\BkashExceptionHandler;
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


        $this->app->bind("CheckoutUrl", function () {
            return new CheckoutUrl();
        });

        $this->loadViewsFrom(__DIR__ . '/Views', 'bkash');



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

        $this->renderBkashException();
        // //
    }

    protected function renderBkashException()
    {
        $this->app->bind(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            BkashExceptionHandler::class
        );
    }
}
