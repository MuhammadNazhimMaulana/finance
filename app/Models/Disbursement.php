<?php

namespace App\Models;

use App\Scopes\CreatedDateDescScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disbursement extends BaseModel
{
    use SoftDeletes;

    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';

    const BONUS = 'BONUS';
    const BATCH = 'BATCH';

    protected $casts = [
        'xendit_data' => 'array',
        'transferred_by' => 'array',
    ];

    public function batches()
    {
        return $this->belongsToMany('App\Models\Batch');
    }

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
