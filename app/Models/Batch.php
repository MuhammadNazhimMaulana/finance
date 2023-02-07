<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    public function disbursements()
    {
        return $this->belongsToMany('App\Models\Disbursement');
    }
}
