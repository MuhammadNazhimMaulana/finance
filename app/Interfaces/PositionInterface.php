<?php

namespace App\Interfaces;

use App\Http\Requests\PositionRequest;

interface PositionInterface
{
    public function index();

    public function store(PositionRequest $request);

    public function update(int $id, PositionRequest $request);

    public function destroy(int $id);
}
