# Laravel Extra Fields Validator

[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]
[![ScrutinizerCI][ico-scrutinizer]][link-scrutinizer]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

## Description

This package allows to perform validation of exta (redundant) fields that were sent with HTTP request.

All you need to do is create new [Form Request](https://laravel.com/docs/6.x/validation#form-request-validation)
by extending `Laravel\ExtraFieldsValidator\ExtraFormRequest` class (instead of `Illuminate\Foundation\Http\FormRequest`).

Since the `ExtraFormRequest` extends the original `FormRequest` class, you can work with this class just as with regular
Form Request.

Behind the scenes `ExtraFormRequest` creates custom instance of Validator class (`Laravel\ExtraFieldsValidator\Validator`)
that allows to register & run extra callbacks (after successful validation & after failed validation).

After successful validation `ExtraFormRequest` will try to find fields that are not described in `rules` list
but present in actual HTTP request payload.

If there's any, response will finish with `HTTP 422 Unprocessable Entity` response and custom error message
will be added to errors bag (`ExtraFormRequest::getExtraFieldErrorMessage(string $field): string`).

## Requirements

This package requires PHP 7.4 or higher.

## Installation

You can install the package via composer:

``` bash
$ composer require tzurbaev/laravel-extra-fields-validator
```

## Usage

Let's say you have the following request:

```php
<?php

namespace App\Http\Requests\Users;

use Laravel\ExtraFieldsValidator\ExtraFormRequest;

class StoreUserRequest extends ExtraFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
        ];
    }
}
```

If actual HTTP request payload contains any other field, validation will fail.

### Using custom validator

If you're creating custom validator via `FormRequest`'s `validator` method, you need to use result of
`parent::validator()` method as your base validator.

```php
<?php

namespace App\Http\Requests\Users;

use Laravel\ExtraFieldsValidator\ExtraFormRequest;

class StoreUserRequest extends ExtraFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
        ];
    }
    
    public function validator()
    {
        $validator = parent::validator();

        // Some custom logic.

        return $validator;
    }
}
```

### Using custom form request

If you're using custom `FormRequest` class and can't extend from `ExtraFormRequest`, you can include
`Laravel\ExtraFieldsValidator\ProvidesExtraFieldsValidator` trait into your base/child class.

### Error message

You need to add message to your `validation.php` language file (under the `custom.extra_field` path). The extra field
name will be passed as `:attribute` replacement.

You can also override `ExtraFormRequest::getExtraFieldErrorMessage` method and return any custom message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email zurbaev@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://poser.pugx.org/tzurbaev/laravel-extra-fields-validator/version?format=flat
[ico-license]: https://poser.pugx.org/tzurbaev/laravel-extra-fields-validator/license?format=flat
[ico-travis]: https://api.travis-ci.org/tzurbaev/laravel-extra-fields-validator.svg?branch=master
[ico-styleci]: https://styleci.io/repos/235513097/shield?branch=master&style=flat
[ico-scrutinizer]: https://scrutinizer-ci.com/g/tzurbaev/laravel-extra-fields-validator/badges/quality-score.png?b=master

[link-packagist]: https://packagist.org/packages/tzurbaev/laravel-extra-fields-validator
[link-travis]: https://travis-ci.org/tzurbaev/laravel-extra-fields-validator
[link-styleci]: https://styleci.io/repos/235513097
[link-scrutinizer]: https://scrutinizer-ci.com/g/tzurbaev/laravel-extra-fields-validator/
[link-author]: https://github.com/tzurbaev
