# Pjax plugin for CakePHP

[![Build Status](https://img.shields.io/travis/slince/cakephp-pjax/master.svg?style=flat-square)](https://travis-ci.org/slince/cakephp-pjax)
[![Coverage Status](https://img.shields.io/codecov/c/github/slince/cakephp-pjax.svg?style=flat-square)](https://codecov.io/github/slince/cakephp-pjax)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/cakephp-pjax.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/cakephp-pjax)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/cakephp-pjax.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/cakephp-pjax/?branch=master)

This plugin for version 3 the CakePHP Framework. [Pjax](https://github.com/defunkt/jquery-pjax) is a jQuery plugin that leverages ajax to 
speed up the loading time of your pages. It works by only fetching specific html fragments
from the server, and client-side updating only happens on certain parts of the page.

The package provides a middleware that can return the response that the jQuery plugin expects.

## Installation

You can install the package via composer:
``` bash
$ composer require slince/cakephp-pjax
```

## Load Plugin

Add the following to your config/bootstrap.php to load the plugin.

```php
Plugin::load('Slince/Pjax', [
    'bootstrap' => true,
]);
```

## Usage

The provided middleware provides [the behaviour that the pjax plugin expects of the server](https://github.com/defunkt/jquery-pjax#server-side):

Checks pjax request:

```php
class PagesController
{
    public function index()
    {
        debug($this->request->is('pjax')); //true
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
