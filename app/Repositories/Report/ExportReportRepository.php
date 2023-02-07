<?php

namespace App\Repositories\Report;;

use App\Interfaces\Report\ExportReportInterface;
use App\Http\Requests\Report\StoreRequest;
use App\Models\{AccountCode, Disbursement};
use Rap2hpoutre\FastExcel\FastExcel;
use App\Repositories\XenditRepository;
use App\Traits\BugsnagTrait;
use Carbon\Carbon;
use Throwable;

class ExportReportRepository implements ExportReportInterface
{
    use BugsnagTrait;
    const PER_PAGE = 20;

    public function index()
    {
        try {
            // Getting account
            $account = AccountCode::paginate(self::PER_PAGE);

            $data = XenditRepository::reports();

            return view('report.export.index', [
                'data' => $data,
                'accounts' => $account
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $filename = 'laporan_'.strtoupper($request->category).'_'.$request->from.'_'.$request->to;

            return (new FastExcel($this->buildLocalReport($request)))->download($filename.'.xlsx');
            
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    private function buildLocalReport(StoreRequest $request)
    {
        $query = Disbursement::query();
        $query->whereBetween('created_at', [$request->from, $request->to]);
        $query->where('category', strtoupper($request->category));
        if($request->status !== 'ALL')
        {
            $query->where('status', $request->status);
        }

        $data = $query->cursor();

        foreach ($data as $item) {
            yield [
                'Tanggal' => Carbon::parse($item->created_at)->format('d F Y'),
                'Nama' => $item->to_name,
                'Cabang' => $item->externalbranch ? $item->externalbranch->external_branch_name : null,
                'Nominal' => number_format($item->amount),
                'Fee' => number_format( env('CASH_OUT_TOTAL_FEE')),
                'Total' => number_format($item->amount +  env('CASH_OUT_TOTAL_FEE')),
                'Bank Tujuan' => $item->bank_name."\n".$item->bank_account_number."\n".$item->bank_account_holder_name,
                'Keterangan' => $item->description,
                'Status' => $item->status,
                'Oleh' => $item->transferred_by['name']
            ];
        }
    }
}
