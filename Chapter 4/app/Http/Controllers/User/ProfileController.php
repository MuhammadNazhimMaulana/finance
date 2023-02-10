<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\UpdateRequest;
use App\Interfaces\User\ProfileInterface;

class ProfileController extends Controller
{
    public function __construct(ProfileInterface $profileInterface)
    {
        $this->profileInterface = $profileInterface;
    }

    public function index()
    {
        return $this->profileInterface->index();
    }

    public function update(UpdateRequest $request)
    {
        return $this->profileInterface->update($request);
    }
}
