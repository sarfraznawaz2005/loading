[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

# Laravel Loading Indicator

## Introduction ##

Laravel package to add loading indicator to pages while page is loading. Loading indicator is automatically removed after page has fully loaded.

Behind the scene, middleware is used to inject needed HTML/CSS/JS before `</body>` tag to show and hide the loading indicator accordingly.

Loading indicators are via to awesome [CSSPIN](https://github.com/webkul/csspin) project.

## Requirements ##

 - PHP >= 7
 - Laravel 5

## Installation ##

Install via composer

```
composer require sarfraznawaz2005/loading
```

For Laravel < 5.5:

Add Service Provider to `config/app.php` in `providers` section

```php
Sarfraznawaz2005\Loading\ServiceProvider::class,
```

---

Publish package's config file by running below command:

```bash
$ php artisan vendor:publish --provider="Sarfraznawaz2005\Loading\ServiceProvider"
```
It should publish `config/loading.php` config file.

## Usage ##

Simply add to `\Sarfraznawaz2005\Loading\Http\Middleware\LoadingMiddleware::class` to `app/Http/Kernel.php` either in global middleware section or route section.

Add in global section if you want loading indicator on all pages automatically or add it to route middleware section if you want to add indictor to certain pages only via `middleware` method in routes.

Visit any page and you should see loading indicator at middle of the page while page is loading.

Check config file for options.


## Credits

- [Sarfraz Ahmed][link-author]
- [All Contributors][link-contributors]

## License

Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sarfraznawaz2005/loading.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sarfraznawaz2005/loading.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sarfraznawaz2005/loading
[link-downloads]: https://packagist.org/packages/sarfraznawaz2005/loading
[link-author]: https://github.com/sarfraznawaz2005
[link-contributors]: https://github.com/sarfraznawaz2005/loading/graphs/contributors
