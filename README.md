# bKash Laravel Package

## Installation

1. Install the package using Composer:
   ```bash
   composer require mahedi250/bkash

  2.To publish the package assets, run:

```bash
php artisan vendor:publish --provider="Mahedi250\Bkash\bkashServiceProvider"


To generate a checkout URL and redirect the user, use the following code:

```php
use Mahedi250\Bkash\Facade\CheckoutUrl;

return redirect(CheckoutUrl::Create(100)["bkashURL"]);


