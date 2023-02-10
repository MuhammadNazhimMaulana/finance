<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Pin\StoreRequest;
use App\Interfaces\User\UserPinInterface;

class PinController extends Controller
{
    public function __construct(UserPinInterface $userPinInterface)
    {
        $this->userPinInterface = $userPinInterface;
    }

    public function store(StoreRequest $request)
    {
        return $this->userPinInterface->store($request);
    }
}
