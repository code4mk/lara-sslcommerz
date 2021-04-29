<p align="center" ><img src="https://raw.githubusercontent.com/code4mk/lara-sslcommerz/master/ssl.PNG"></p


SslCommerz is the Bangladeshi most favorite payment gateway.

# Installation

```bash
composer require code4mk/lara-sslcommerz
```

# setup

## vendor publish

```bash
php artisan vendor:publish --provider="Code4mk\Sslcommerz\SslcommerzServiceProvider" --tag=config
```

## service provider  ) if you are using Laravel before `version 5.4`, manually register the service provider in your config/app.php file

```php
Code4mk\Sslcommerz\SslcommerzServiceProvider::class
```

## env

```bash
SSLCOMMERZ_STORE_ID=""
SSLCOMMERZ_STORE_PASSWORD=""
SSLCOMMERZ_SUCCESS_URL="http://127.0.0.1:8000/success"
SSLCOMMERZ_FAIL_URL="http://127.0.0.1:8000"
SSLCOMMERZ_CANCEL_URL="http://127.0.0.1:8000"
SSLCOMMERZ_MODE=sandbox
```

* SSLCOMMERZ_MODE (sandbox or live)

# Get Redirect url

```php
$data = SslPayment::tnx(2)
          ->customer('kamal212')
          ->amount(100)
          ->emi(4,5,1)
          ->getRedirectUrl();

if( $data->failedreason == "") {
    $link = $data->GatewayPageURL;
    return response()->json($link);
}else{
  // your code
    return response()->json($data->failedreason);
}
```

## methods

* emi($max_inst = '',$selected_inst = '',$allow_only = '')
* customer($name = '', $email = '', $phone = '', $add1 = '', $city = '', $post_code = '', $country = '')


# verify payment (`post method`)

```php
$data = SslPayment::verify(request());
// return response()->json($data);
if ($data->status == 'VALID') {
    // your business logic
 }
```

# ipn payment verify

create a post route for ipn and csrf free

* [ipn doc sslcommerz](https://developer.sslcommerz.com/doc/v4/#validate-payment-with-ipn)

```php
/* you can get success objects which triggers by sslcomerz.
  you can
*/
Route::post('ipn',function(){
  return response()->json(request());
})
```

# csrf

* enable csrf free for your success route

ex : (VerifyCsrfToken.php)

```php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/success',
        '/ipn'
    ];
}

```

# Demo

* [Lara sslcommerz Demo](https://github.com/code4mk/lara-sslcommerz-demo)

# Any query

* hiremostafa@gmail.com
