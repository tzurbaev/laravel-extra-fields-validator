<?php

declare(strict_types=1);

namespace Laravel\ExtraFieldsValidator;

use Illuminate\Foundation\Http\FormRequest;

class ExtraFormRequest extends FormRequest
{
    use ProvidesExtraFieldsValidator;
}
