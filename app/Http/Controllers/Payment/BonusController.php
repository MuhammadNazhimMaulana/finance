<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Interfaces\Payment\BonusInterface;
use App\Http\Requests\Payment\Salary\FilterRequest;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function __construct(BonusInterface $bonusInterface)
    {
        $this->bonusInterface = $bonusInterface;
    }

    public function index(FilterRequest $request)
    {
        return $this->bonusInterface->index($request);
    }

    public function storeBonusConfig(Request $request)
    {
        return $this->bonusInterface->storeBonusConfig($request);
    }
}
