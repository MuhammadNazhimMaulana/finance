<?php

namespace App\Repositories\Report;;

use App\Interfaces\Report\DisbursementReportInterface;
use App\Http\Requests\Payment\Disbursement\FilterRequest;
use App\Models\Disbursement;
use App\Traits\BugsnagTrait;
use Throwable;

class DisbursementReportRepository implements DisbursementReportInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index(string $category, FilterRequest $request)
    {
        try {
            $category = strtoupper($category);
            $title = 'Laporan '.ucfirst(strtolower($category));

            $query = Disbursement::query();
            $query->where('category', $category);
            if ($request->filled('to_name')) {
                $name = strip_tags($request->to_name);
                $query->where('to_name', 'ilike', "%$name%");
            }
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }
            if ($request->filled('amount')) {
                $query->where('amount', $request->amount);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $data = $query->paginate(self::PER_PAGE);

            return view('report.disbursement.index', [
                'data' => $data,
                'title' => $title
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }
}
