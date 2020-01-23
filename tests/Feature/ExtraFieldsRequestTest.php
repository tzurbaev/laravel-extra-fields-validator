<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Laravel\ExtraFieldsValidator\ExtraValidatorDataSource;
use Tests\Fakes\FakeController;
use Tests\TestCase;

class ExtraFieldsRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/testing', FakeController::class.'@index')->name('testing.index');
        Route::post('/testing', FakeController::class.'@store')->name('testing.store');
    }

    /**
     * @param array       $payload
     * @param int         $status
     * @param string|null $field
     * @dataProvider extraFieldsDataProvider
     */
    public function testItShouldValidateExtraFields(array $payload, int $status, ?string $field = null)
    {
        $response = $this->json('POST', route('testing.store'), $payload)
            ->assertStatus($status);

        if (!is_null($field)) {
            $response->assertJsonValidationErrors($field);
        }
    }

    public function testItShouldValidateExtraFieldsFromQueryWithDefaultDataSource()
    {
        $payload = [
            'users' => [
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'stores' => [
                        ['name' => 'Apple Store'],
                    ],
                ],
            ],
        ];

        config()->set('extra-validator.data_source', ExtraValidatorDataSource::DEFAULT);

        $this->json('POST', route('testing.store', ['hello' => 'world']), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors('hello');
    }

    public function testItShouldNotValidateExtraFieldsFromQueryWithInputSource()
    {
        $payload = [
            'users' => [
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'stores' => [
                        ['name' => 'Apple Store'],
                    ],
                ],
            ],
        ];

        config()->set('extra-validator.data_source', ExtraValidatorDataSource::INPUT_SOURCE);

        $this->json('POST', route('testing.store', ['hello' => 'world']), $payload)
            ->assertStatus(200);
    }

    public function testItShouldValidateExtraFieldsFromQueryWithDefaultDataSourceForGetMethods()
    {
        config()->set('extra-validator.data_source', ExtraValidatorDataSource::DEFAULT);

        $this->json('GET', route('testing.index', ['hello' => 'world']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('hello');
    }

    public function testItShouldNotValidateExtraFieldsFromQueryWithInputSourceForGetMethod()
    {
        config()->set('extra-validator.data_source', ExtraValidatorDataSource::INPUT_SOURCE);

        $this->json('GET', route('testing.index', ['hello' => 'world']))
            ->assertStatus(200);
    }

    public function extraFieldsDataProvider()
    {
        return [
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ],
                    ],
                ],
                'status' => 200,
            ],
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'age' => 25,
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ],
                    ],
                ],
                'status' => 422,
                'field' => 'users.0.age',
            ],
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'age' => 25,
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ],
                    ],
                ],
                'status' => 422,
                'field' => 'users.0.last_name',
            ],
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'age' => 25,
                            'stores' => [],
                        ],
                    ],
                ],
                'status' => 422,
                'field' => 'users.0.stores',
            ],
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ],
                    ],
                    'message' => 'Hello, world',
                ],
                'status' => 422,
                'field' => 'message',
            ],
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ],
                    ],
                    'messages' => [
                        'Hello, world',
                    ],
                ],
                'status' => 422,
                'field' => 'messages.0',
            ],
            [
                'payload' => [
                    'users' => [
                        [
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ],
                    ],
                    'messages' => [
                        ['text' => 'Hello, world'],
                    ],
                ],
                'status' => 422,
                'field' => 'messages.0.text',
            ],
            [
                'payload' => [
                    'users' => collect(array_fill(0, 99, null))
                        ->map(function () {
                            return [
                                'first_name' => 'John',
                                'last_name' => 'Doe',
                                'stores' => [
                                    ['name' => 'Apple Store'],
                                ],
                            ];
                        })
                        ->push([
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'age' => 25,
                            'stores' => [
                                ['name' => 'Apple Store'],
                            ],
                        ])
                        ->toArray(),
                ],
                'status' => 422,
                'field' => 'users.99.age',
            ],
        ];
    }
}
