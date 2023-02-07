<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    const SALARY_PAYMENT_DATE = 'SALARY_PAYMENT_DATE';
    const BONUS_TYPE = 'BONUS_TYPE';
    const BONUS_PAYMENT_DATE = 'BONUS_PAYMENT_DATE';
    const BONUS_PAYMENT_PERCENTAGE = 'BONUS_PAYMENT_PERCENTAGE';

    const TAHUNAN = 'tahunan';
    const BULANAN = 'bulanan';

    protected $fillable = ['name', 'value'];
}
