<?php

namespace App\Repositories\Payment;;

use App\Interfaces\Payment\BalanceInterface;
use App\Repositories\XenditRepository;
use App\Traits\BugsnagTrait;
use Throwable;

class BalanceRepository implements BalanceInterface
{
    use BugsnagTrait;

    public function show(string $type)
    {
        try {
            $res = XenditRepository::getBalance($type);

            return response()->json($res);
        } catch (Throwable $e) {
            $this->report($e);
            return response()->json(null, 400);
        }
    }
}
