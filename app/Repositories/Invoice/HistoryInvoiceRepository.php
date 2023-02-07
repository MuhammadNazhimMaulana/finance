<?php

namespace App\Repositories\Invoice;;

use App\Interfaces\Invoice\HistoryInvoiceInterface;
use App\Models\ManualInvoice;
use App\Traits\BugsnagTrait;
use App\User;
use Throwable;

class HistoryInvoiceRepository implements HistoryInvoiceInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            // Getting user id
            $user = User::find(auth()->user()->id);

            $request = request();

            $res = ManualInvoice::where('external_id', 'like', $user->id. '%');
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('company_name', $name)->orWhere('contact_name', $name);
            }

            $data = $res->orderBy('created_at', 'DESC')->paginate(self::PER_PAGE);

            return view('historyInovice.index', [
                'data' => $data,
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }
}
