# Laravel Jwt Auth

[![Build Status](https://travis-ci.org/lucandrade/laravel-jwtauth.svg?branch=master)](https://travis-ci.org/lucandrade/laravel-jwtauth)
[![Codacy Badge](https://api.codacy.com/project/badge/5e61cfb579dd4faf939f24cdef401cdc)](https://www.codacy.com/app/lucas-andrade-oliveira/laravel-jwtauth)

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

Run the `vendor:publish` command:

```
php artisan vendor:publish
```

## Configuration - Lumen

Execute this command from your project path:

```
cp ./vendor/lucasandrade/laravel-jwtauth/src/config/jwtauth.php ./config
```

Uncomment the following line of your `bootstrap/app.php` file:

```
\\ $app->withFacades();
```

Add this line in the end of file:

```
$app->register(Lucandrade\JwtAuth\Lumen\JwtAuthServiceProvider::class);
```

> **Note:** remember to add `use JwtAuth;` to the beginning of the yours class file