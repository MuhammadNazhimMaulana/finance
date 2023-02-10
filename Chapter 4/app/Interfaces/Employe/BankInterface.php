<?php

namespace App\Interfaces\Employe;

use App\Http\Requests\Employe\BankRequest;

interface BankInterface
{
    public function index(int $id);

    public function store(BankRequest $request);

    public function update(int $id, BankRequest $request);

    public function destroy(int $id);
}
