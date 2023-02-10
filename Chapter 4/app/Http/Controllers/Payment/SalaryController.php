<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Interfaces\Payment\SalaryInterface;
use App\Http\Requests\Payment\Salary\{FilterRequest, StoreRequest};
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function __construct(SalaryInterface $salaryInterface)
    {
        $this->salaryInterface = $salaryInterface;
    }

    public function index(FilterRequest $request)
    {
        return $this->salaryInterface->index($request);
    }

    public function store(StoreRequest $request)
    {
        return $this->salaryInterface->store($request);
    }

    public function storeDate(Request $request)
    {
        return $this->salaryInterface->storeDate($request);
    }
}
