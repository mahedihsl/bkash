# bKash Laravel Package

Welcome to the bKash Laravel Package! This package allows for seamless integration with the bKash payment gateway, making transactions a breeze.


## Installation

```bash
composer require mahedi250/bkash
```

### vendor publish (config)

```bash
php artisan vendor:publish --provider="Mahedi250\Bkash\bkashServiceProvider"
```
## Usage

### 1. Create Payment

```
use Mahedi250\Bkash\Facade\CheckoutUrl;

return redirect(CheckoutUrl::Create(100)["bkashURL"]);
