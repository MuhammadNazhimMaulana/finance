<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class UserLog extends BaseModel
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('orderByCreatedAtDesc', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
