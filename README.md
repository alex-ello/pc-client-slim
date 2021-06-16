# Parts Catalogs Client

Simple client for api.parts-catalogs.com based on PHP Slim framework.

![Preview](preview.png)

## Install

1. Clone this repo
1. Install dependency
  `composer install`
1. Insert youre api key to [src/settings.php](src/settings.php)
```php
'partsCatalogs' => [
    'apiKey' => 'YOUR_API_KEY_HERE',
]
```
1. Run on php built in server
   ```php -S 127.0.0.1:8080 -t public```
1. Open in browser http://127.0.0.1:8080
