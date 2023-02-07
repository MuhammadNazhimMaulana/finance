<?php

namespace App\Http\Controllers;

use App\Interfaces\AccountCodeInterface;
use App\Http\Requests\AccountRequest;

class AccountCodeController extends Controller
{
    public function __construct(AccountCodeInterface $accountCodeInterface)
    {
        $this->accountCodeInterface = $accountCodeInterface;
    }

    public function index()
    {
        return $this->accountCodeInterface->index();
    }

    public function store(AccountRequest $request)
    {
        return $this->accountCodeInterface->store($request);
    }
    
    public function update(int $id, AccountRequest $request)
    {
        return $this->accountCodeInterface->update($id, $request);
    }

    public function destroy(int $id)
    {
        return $this->accountCodeInterface->destroy($id);
    }
}
