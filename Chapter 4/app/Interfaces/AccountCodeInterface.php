<?php

namespace App\Interfaces;

use App\Http\Requests\AccountRequest;

interface AccountCodeInterface
{
    public function index();

    public function store(AccountRequest $request);

    public function update(int $id,AccountRequest $request);

    public function destroy(int $id);
}
