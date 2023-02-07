<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Interfaces\Employe\BankInterface;
use App\Http\Requests\Employe\BankRequest;

class BankController extends Controller
{
    public function __construct(BankInterface $bankInterface)
    {
        $this->bankInterface = $bankInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        return $this->bankInterface->index($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Employe\BankRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        return $this->bankInterface->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Employe\BankRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BankRequest $request, int $id, int $bankId)
    {
        return $this->bankInterface->update($bankId, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, int $bankId)
    {
        return $this->bankInterface->destroy($bankId);
    }
}
