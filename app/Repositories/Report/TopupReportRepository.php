<?php

namespace App\Repositories\Report;;

use App\Http\Requests\Payment\Topup\FilterRequest;
use App\Interfaces\Report\TopupReportInterface;
use App\Traits\BugsnagTrait;
use App\Models\Topup;
use Throwable;

class TopupReportRepository implements TopupReportInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index(FilterRequest $request)
    {
        try {
            $res = Topup::query();
            $datas = Topup::query();
            if ($request->filled('amount')) {
                $res->where('amount', $request->amount);
                $datas->where('amount', $request->amount);
            }
            if ($request->date) {
                $res->whereDate('created_at', $request->date);
                $datas->whereDate('created_at', $request->date);
            }

            $successTopupAmount = $res->whereIn('status', [Topup::STATUS_PAID, Topup::STATUS_SETTLED])->sum('amount');

            $data = $datas->paginate(self::PER_PAGE);

            return view('report.topup.index', [
                'data' => $data,
                'successTopupAmount' => $successTopupAmount
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }
}
