# Laravel Stanbic

[![PHPStan](https://github.com/akikadigital/laravel-stanbic/actions/workflows/phpstan.yml/badge.svg)](https://github.com/akikadigital/laravel-stanbic/actions/workflows/phpstan.yml)
[![run-tests](https://github.com/akikadigital/laravel-stanbic/actions/workflows/run-tests.yml/badge.svg)](https://github.com/akikadigital/laravel-stanbic/actions/workflows/run-tests.yml)

An unofficial package for Stanbic

## Installation

You can install the package via composer:

```bash
composer require akika/laravel-stanbic
```

Optionally, You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-stanbic-config"
```

## Usage

```php
$laravelStanbic = new Akika\LaravelStanbic();
echo $laravelStanbic->echoPhrase('Hello, Akika!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Akika Digital](https://github.com/akika)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
