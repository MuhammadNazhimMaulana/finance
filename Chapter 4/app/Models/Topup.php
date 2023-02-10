<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CreatedDateDescScope;

class Topup extends BaseModel
{
    use SoftDeletes;

    const STATUS_PAID = 'PAID';
    const STATUS_PENDING = 'PENDING';
    const STATUS_SETTLED = 'SETTLED';
    const STATUS_EXPIRED = 'EXPIRED';

    protected $casts = [
        'xendit_data' => 'array',
        'user_data' => 'array',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CreatedDateDescScope);
    }
}
