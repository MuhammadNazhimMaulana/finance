<?php

namespace App\Models;

use App\Scopes\CreatedDateDescScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeBank extends BaseModel
{
    use SoftDeletes;
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
    
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
