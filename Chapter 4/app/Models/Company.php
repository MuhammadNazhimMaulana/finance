<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\NameAscScope;

class Company extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['name'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new NameAscScope);
    }
}
