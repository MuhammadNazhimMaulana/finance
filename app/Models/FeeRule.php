<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeRule extends Model
{
    const PERCENT_RULE = 100;
    const VIRTUAL_ACCOUNT = 'VIRTUAL_ACCOUNT';
    const PERCENT = 'percent';
}
