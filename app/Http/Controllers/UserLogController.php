<?php

namespace App\Http\Controllers;

use App\Interfaces\UserLogInterface;

class UserLogController extends Controller
{
    public function __construct(UserLogInterface $userLogInterface)
    {
        $this->userLogInterface = $userLogInterface;
    }

    public function index()
    {
        return $this->userLogInterface->index();
    }
}
