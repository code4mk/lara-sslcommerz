<?php

namespace Code4mk\Sslcommerz;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

/**
 * Service Provider class
 * @author code4mk <hiremostafa@gmail.com>
 */
 
class SslcommerzServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
   public function boot()
   {
       $this->publishes([
        __DIR__ . '/../config/sslcommerz.php' => config_path('sslcommerz.php'),
      ], 'config');

       AliasLoader::getInstance()->alias('SslPayment', 'Code4mk\Sslcommerz\Facades\SslCommerz');
   }

  /**
   * Register any application services.
   *
   * @return void
   */
   public function register()
   {
       $this->app->bind('sslCommerz', function () {
           return new Sslcommerz;
       });
   }
}
