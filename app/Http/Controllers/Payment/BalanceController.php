<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Interfaces\Payment\BalanceInterface;

class BalanceController extends Controller
{
    public function __construct(BalanceInterface $balanceInterface)
    {
        $this->balanceInterface = $balanceInterface;
    }

    public function show(string $type)
    {
        return $this->balanceInterface->show($type);
    }
}
