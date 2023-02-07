<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Interfaces\Report\SalaryReportInterface;
use App\Http\Requests\Payment\Salary\FilterRequest;

class SalaryController extends Controller
{
    public function __construct(SalaryReportInterface $salaryReportInterface)
    {
        $this->salaryReportInterface = $salaryReportInterface;
    }

    public function index(FilterRequest $request)
    {
        return $this->salaryReportInterface->index($request);
    }
}
