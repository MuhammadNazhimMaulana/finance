<?php

namespace App\Interfaces;

use App\Http\Requests\CompanyRequest;

interface CompanyInterface
{
    public function index();

    public function store(CompanyRequest $request);

    public function update(int $id, CompanyRequest $request);

    public function destroy(int $id);
}
