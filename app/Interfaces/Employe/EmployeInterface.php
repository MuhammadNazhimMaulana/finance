<?php

namespace App\Interfaces\Employe;

use App\Http\Requests\Employe\EmployeRequest;

interface EmployeInterface
{
    public function index();

    public function store(EmployeRequest $request);

    public function update(int $id, EmployeRequest $request);

    public function destroy(int $id);
}
