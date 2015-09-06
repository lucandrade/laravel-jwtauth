# Laravel Jwt Auth

## Compatibility

 Laravel  | PHP
:---------|:----------
 5.0.x    | >= 5.4
 5.1.x    | >= 5.5.9

## Installation

Add the following line to your `composer.json` file:

```
"lucasandrade/laravel-jwtauth": "dev-master"
```

Then run `composer update` to get the package.

## Configuration - Laravel

Add this line of code to the `providers` array located in your `config/app.php` file:

```
Lucandrade\JwtAuth\JwtAuthServiceProvider::class,
```

Add this line to the `aliases` array:

```
'JwtAuth' => \Lucandrade\JwtAuth\Facades\JwtAuth::class,
```