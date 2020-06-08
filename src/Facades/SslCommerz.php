<?php

namespace Code4mk\Sslcommerz\Facades;

use Illuminate\Support\Facades\Facade;

class SslCommerz extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
      return 'sslCommerz';
  }
}
