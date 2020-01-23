<?php

declare(strict_types=1);

namespace Tests\Fakes;

use Laravel\ExtraFieldsValidator\ExtraFormRequest;

class FakeEmptyRequest extends ExtraFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}
