<?php

namespace App\Interfaces\Payment;

use App\Http\Requests\Payment\Salary\{FilterRequest, StoreRequest};
use Illuminate\Http\Request;

interface SalaryInterface
{
    public function index(FilterRequest $request);

    public function store(StoreRequest $request);

    public function storeDate(Request $request);
}
