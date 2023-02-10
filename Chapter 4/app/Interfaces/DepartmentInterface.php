<?php

namespace App\Interfaces;

use App\Http\Requests\DepartmentRequest;

interface DepartmentInterface
{
    public function index();

    public function store(DepartmentRequest $request);

    public function update(int $id, DepartmentRequest $request);

    public function destroy(int $id);
}
