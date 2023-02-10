<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Interfaces\Employe\EmployeInterface;
use App\Http\Requests\Employe\EmployeRequest;

class EmployeController extends Controller
{
    public function __construct(EmployeInterface $employeInterface)
    {
        $this->employeInterface = $employeInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->employeInterface->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Employe\EmployeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeRequest $request)
    {
        return $this->employeInterface->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Employe\EmployeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeRequest $request, int $id)
    {
        return $this->employeInterface->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->employeInterface->destroy($id);
    }
}
