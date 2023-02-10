<?php

namespace App\Interfaces;

use App\Http\Requests\BranchRequest;

interface BranchInterface
{
    public function index();

    public function store(BranchRequest $request);

    public function update(int $id, BranchRequest $request);

    public function destroy(int $id);
}
