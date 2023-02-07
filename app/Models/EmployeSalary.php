<?php

namespace App\Models;

use App\Scopes\CreatedDateDescScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class EmployeSalary extends BaseModel
{
    use SoftDeletes;

    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';

    protected $casts = [
        'xendit_data' => 'array',
        'meta_data' => 'array',
        'employe_data' => 'array',
        'employe_bank_data' => 'array',
        'transferred_by' => 'array',
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

    public function employe()
    {
        return $this->belongsTo('App\Models\Employe')->withTrashed();
    }

    public function employebank()
    {
        return $this->belongsTo('App\Models\EmployeBank')->withTrashed();
    }

    public function scopeOfCompany($query, $id)
    {
        return $query->whereHas('employe.company', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfBranch($query, $id)
    {
        return $query->whereHas('employe.branch', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfDepartment($query, $id)
    {
        return $query->whereHas('employe.department', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfPosition($query, $id)
    {
        return $query->whereHas('employe.position', function (Builder $query) use ($id) {
            $query->where('id', $id);
        });
    }

    public function scopeOfSalary($query, $month, $year)
    {
        return $query->whereYear('salary_date', $year)->whereMonth('salary_date', $month);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
