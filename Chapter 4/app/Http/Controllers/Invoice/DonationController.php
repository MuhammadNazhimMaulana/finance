<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Interfaces\Invoice\DonationInterface;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(DonationInterface $donationInterface)
    {
        $this->donationInterface = $donationInterface;
    }

    public function donationBanks()
    {
        return $this->donationInterface->donationBanks();
    }

    public function index()
    {
        return $this->donationInterface->index();
    }

    public function personResponsible()
    {
        return $this->donationInterface->personResponsible();
    }

    public function storePersonResponsible(Request $request)
    {
        return $this->donationInterface->storePersonResponsible($request);
    }

    public function updatePersonResponsible(Request $request, int $id)
    {
        return $this->donationInterface->updatePersonResponsible($request, $id);
    }

    public function destroyPersonResponsible(int $id)
    {
        return $this->donationInterface->destroyPersonResponsible($id);
    }

    public function store(CreateInvoiceRequest $request)
    {
        return $this->donationInterface->store($request);
    }
}
