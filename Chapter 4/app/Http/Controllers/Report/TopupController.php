<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Interfaces\Report\TopupReportInterface;
use App\Http\Requests\Payment\Topup\FilterRequest;

class TopupController extends Controller
{
    public function __construct(TopupReportInterface $topupReportInterface)
    {
        $this->topupReportInterface = $topupReportInterface;
    }

    public function index(FilterRequest $request)
    {
        return $this->topupReportInterface->index($request);
    }
}
