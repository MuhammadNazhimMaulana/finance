<?php

namespace App\Interfaces;
use App\Http\Requests\RequestContact;

interface ContactInterface
{
    public function index(string $account_code);

    public function transaction();

    public function contact(string $account_code);

    public function store(RequestContact $request);

    public function update(int $id, RequestContact $request);

    public function destroy(int $id);
}
