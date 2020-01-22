<?php

declare(strict_types=1);

namespace Tests\Unit;

use Laravel\ExtraFieldsValidator\ExtraFieldsDetector;
use Tests\TestCase;

class ExtraFieldsDetectorTest extends TestCase
{
    /**
     * @param array       $data
     * @param array       $rules
     * @param string|null $expected
     * @dataProvider extraFieldsDataProvider
     */
    public function testItShouldDetectExtraFields(array $data, array $rules, ?string $expected)
    {
        $detector = new ExtraFieldsDetector();
        $this->assertSame($expected, $detector->getFirstExtraField($data, $rules));
    }

    public function extraFieldsDataProvider()
    {
        return [
            [
                'data' => [],
                'rules' => [],
                'field' => null,
            ],
            [
                'data' => [
                    'message' => 'hello, world',
                ],
                'rules' => [],
                'field' => 'message',
            ],
            [
                'data' => [
                    'message' => 'hello, world',
                ],
                'rules' => [
                    'message' => 'required|string',
                ],
                'field' => null,
            ],
            [
                'data' => [
                    'users' => [
                        ['name' => 'John Doe'],
                        ['name' => 'Jane Doe'],
                    ],
                ],
                'rules' => [
                    'users' => 'required|array',
                    'users.*.name' => 'required|string',
                ],
                'field' => null,
            ],
            [
                'data' => [
                    'users' => [
                        ['name' => 'John Doe', 'age' => 25],
                        ['name' => 'Jane Doe', 'age' => 26],
                    ],
                ],
                'rules' => [
                    'users' => 'required|array',
                    'users.*.name' => 'required|string',
                ],
                'field' => 'users.0.age',
            ],
            [
                'data' => [
                    'users' => [
                        ['name' => 'John Doe'],
                        ['name' => 'Jane Doe', 'age' => 26],
                    ],
                ],
                'rules' => [
                    'users' => 'required|array',
                    'users.*.name' => 'required|string',
                ],
                'field' => 'users.1.age',
            ],
            [
                'data' => [
                    'users' => collect(array_fill(0, 1000, null))->map(function () {
                        return ['name' => 'John Doe', 'age' => 25];
                    })->toArray(),
                ],
                'rules' => [
                    'users' => 'required|array|max:100',
                    'users.*.name' => 'required|string',
                ],
                'field' => 'users.0.age',
            ],
        ];
    }
}
