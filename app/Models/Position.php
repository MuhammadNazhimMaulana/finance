<?php

namespace App\Models;

use App\Scopes\NameAscScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends BaseModel
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
