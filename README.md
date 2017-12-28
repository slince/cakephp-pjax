# A pjax middleware for CakePHP 3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-pjax.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-pjax)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-pjax/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-pjax)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/89249e40-536c-4b1b-b1fb-f8b807b2b51d.svg?style=flat-square)](https://insight.sensiolabs.com/projects/89249e40-536c-4b1b-b1fb-f8b807b2b51d)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-pjax.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-pjax)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-pjax.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-pjax)

[Pjax](https://github.com/defunkt/jquery-pjax) is a jQuery plugin that leverages ajax to 
speed up the loading time of your pages. It works by only fetching specific html fragments
from the server, and client-side updating only happens on certain parts of the page.

The package provides a middleware that can return the response that the jQuery plugin expects.

## Installation

You can install the package via composer:
``` bash
$ composer require slince/cakephp-pjax
```

Next you must add the `\Spatie\Pjax\Middleware\FilterIfPjax`-middleware to the kernel.
```php
// app/Http/Kernel.php

...
protected $middleware = [
    ...
    \Spatie\Pjax\Middleware\FilterIfPjax::class,
];
```


## Usage

The provided middleware provides [the behaviour that the pjax plugin expects of the server](https://github.com/defunkt/jquery-pjax#server-side):

> An X-PJAX request header is set to differentiate a pjax request from normal XHR requests. 
> In this case, if the request is pjax, we skip the layout html and just render the inner
> contents of the container.

## Security

If you discover any security related issues, please email taosikai@yeah.net instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
