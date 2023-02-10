<?php

namespace App\Models;

use App\Scopes\CreatedDateDescScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Employe extends BaseModel
{
    use SoftDeletes;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CreatedDateDescScope);
    }

    public function scopeOfCompany($query, $id)
    {
        return $query->whereHas('company', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfBranch($query, $id)
    {
        return $query->whereHas('branch', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfDepartment($query, $id)
    {
        return $query->whereHas('department', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfPosition($query, $id)
    {
        return $query->whereHas('position', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfSalary($query, $month, $year)
    {
        return $query->whereHas('employebanks')->whereDoesntHave('employesalaries', function (Builder $query) use ($month, $year) {
            $query->whereYear('salary_date', $year)->whereMonth('salary_date', $month);
        });
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function employebanks()
    {
        return $this->hasMany('App\Models\EmployeBank');
    }

    public function employesalaries()
    {
        return $this->hasMany('App\Models\EmployeSalary');
    }
}
