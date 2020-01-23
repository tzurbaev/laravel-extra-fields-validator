<?php

declare(strict_types=1);

namespace Tests\Fakes;

use Laravel\ExtraFieldsValidator\ExtraFormRequest;

class FakeRequest extends ExtraFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'users' => 'required|array|max:100',
            'users.*.first_name' => 'required|string|max:255',
            'users.*.last_name' => [
                'required',
                'string',
                'max:255',
            ],
            'users.*.stores' => 'required|array',
            'users.*.stores.*.name' => 'required|string|max:255',
            'users.*.stores.*.members' => 'nullable|array',
            'users.*.stores.*.members.*.full_name' => 'requried|string|max:255',
        ];
    }
}
