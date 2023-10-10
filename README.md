# bKash Laravel Package

Welcome to the bKash Laravel Package! This package allows for seamless integration with the bKash payment gateway, making transactions a breeze.

## Installation

To get started, install the package using Composer:

```bash
composer require mahedi250/bkash

# Publishing Package Assets

This package contains various assets that are essential for its functionality. Before utilizing the package's features, it's important to publish these assets to your Laravel application.

To publish the package assets, follow these steps:

1. Open a terminal and navigate to your Laravel project directory.

2. Run the following command to publish the assets:

   ```bash
   php artisan vendor:publish --provider="Mahedi250\Bkash\bkashServiceProvider"
