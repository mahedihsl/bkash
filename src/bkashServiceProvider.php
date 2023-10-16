<?php

namespace Mahedi250\Bkash;

use Illuminate\Support\ServiceProvider;
use Mahedi250\Bkash\Products\CheckoutUrl;
use Mahedi250\Bkash\App\Exceptions\BkashExceptionHandler;
use Mahedi250\Bkash\App\Service\BkashAuthService;
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

        $this->app->singleton(BkashAuthService::class, function ($app) {
            return new BkashAuthService();
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
