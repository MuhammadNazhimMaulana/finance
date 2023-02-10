<?php

namespace App\Interfaces\Invoice;
use App\Http\Requests\Invoice\CreateInvoiceRequest;

interface DonationInterface
{
    public function donationBanks();

    public function index();
    
    public function personResponsible();
    
    public function storePersonResponsible($request);
    
    public function updatePersonResponsible($request, $id);
    
    public function destroyPersonResponsible($id);

    public function store(CreateInvoiceRequest $request);
}
