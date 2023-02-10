<?php

namespace App\Interfaces\User;

interface UserLogInterface
{
    /** @return mixed  */
    public function index();

    /**
     * @param int $userId
     * @param string $userName
     * @param string $userEmail
     * @param string $action
     * @param null|string $ipAddress
     * @param null|string $userAgent
     * @return mixed
     */
    public function store(int $userId, string $action, ?string $ipAddress, ?string $userAgent);
}
