<?php

namespace App\Interfaces\User;

use App\Http\Requests\User\Pin\StoreRequest;

interface UserPinInterface
{
    public function store(StoreRequest $request);
}
