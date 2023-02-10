<?php

namespace App\Interfaces\Report;

use App\Http\Requests\Report\StoreRequest;

interface ExportReportInterface
{
    public function index();

    public function store(StoreRequest $request);
}
