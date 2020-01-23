<?php

declare(strict_types=1);

namespace Tests\Fakes;

class FakeController
{
    public function index(FakeEmptyRequest $request)
    {
        return response()->json([
            'data' => $request->all(),
        ]);
    }

    public function store(FakeRequest $request)
    {
        return response()->json([
            'data' => $request->validated(),
        ]);
    }
}
