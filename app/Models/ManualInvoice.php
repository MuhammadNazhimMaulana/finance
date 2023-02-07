<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualInvoice extends Model
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'PAID';
    
    public function invoiceItem()
    {
        return $this->hasMany('App\Models\InvoiceItem');
    }

}
