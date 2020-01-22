<?php

declare(strict_types=1);

namespace Tests\Fakes;

class FakeController
{
    public function store(FakeRequest $request)
    {
        return response()->json([
            'data' => $request->validated(),
        ]);
    }
}
