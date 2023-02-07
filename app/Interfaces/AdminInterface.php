<?php

namespace App\Interfaces;

use App\Http\Requests\Admin\{StoreRequest, UpdateRequest};

interface AdminInterface
{
    public function index();

    public function store(StoreRequest $request);

    public function update(int $id, UpdateRequest $request);

    public function destroy(int $id);

    public function restore(int $id);
}
