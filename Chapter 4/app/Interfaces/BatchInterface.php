<?php

namespace App\Interfaces;
use App\Http\Requests\Payment\Batch\StoreRequest;

interface BatchInterface
{
    public function index();
    
    public function retransfer($request);

    public function store(StoreRequest $request);

    public function transferProof(int $disbursement_id);
}
