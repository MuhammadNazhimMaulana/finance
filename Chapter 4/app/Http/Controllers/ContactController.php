<?php

namespace App\Http\Controllers;

use App\Interfaces\ContactInterface;
use App\Http\Requests\RequestContact;

class ContactController extends Controller
{
    public function __construct(ContactInterface $contactInterface)
    {
        $this->contactInterface = $contactInterface;
    }

    public function index(string $account_code)
    {
        return $this->contactInterface->index($account_code);
    }

    public function transaction()
    {
        return $this->contactInterface->transaction();
    }

    public function contact(string $account_code)
    {
        return $this->contactInterface->contact($account_code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestContact  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestContact $request)
    {
        return $this->contactInterface->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestContact  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestContact $request, int $id)
    {
        return $this->contactInterface->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->contactInterface->destroy($id);
    }
}
