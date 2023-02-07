<?php

namespace App\Interfaces\User;

use App\Http\Requests\User\Profile\UpdateRequest;

interface ProfileInterface
{
    public function index();

    public function update(UpdateRequest $request);
}
