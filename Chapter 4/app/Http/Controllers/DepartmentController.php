<?php

namespace App\Http\Controllers;

use App\Interfaces\DepartmentInterface;
use App\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller
{
    public function __construct(DepartmentInterface $departmentInterface)
    {
        $this->departmentInterface = $departmentInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->departmentInterface->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\DepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        return $this->departmentInterface->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\DepartmentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, int $id)
    {
        return $this->departmentInterface->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->departmentInterface->destroy($id);
    }
}
