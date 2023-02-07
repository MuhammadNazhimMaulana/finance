<?php

namespace App\Repositories\Payment;;

use App\Interfaces\Payment\DisbursementInterface;
use App\Http\Requests\Payment\Disbursement\StoreRequest;
use App\Repositories\XenditRepository;
use App\Traits\BugsnagTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Disbursement;
use Throwable;

class DisbursementRepository implements DisbursementInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $admin = $request->user();

            // Validate Pin
            if (!$admin->pin) return back()->with('errorMsg', 'PIN Anda Salah!');
            if (!Hash::check($request->pin, $admin->pin->value)) return back()->with('errorMsg', 'PIN Anda Salah!');

            $category = strtoupper($request->category);
            $todayDate = Carbon::now()->format('Y-m-d');
            $amount = (int) str_replace('.', '', $request->amount);
            $hash = md5($amount.$request->bank_code.$request->bank_account_holder_name.$request->bank_account_number.$todayDate.request()->url());

            $externalId = $category.'-'.$amount.'-'.$hash;

            if ($request->filled('withdraw_id')) {
                $externalId = $category.'-'.$request->withdraw_id.'-'.$amount.'-'.$hash;
            }

            if ($request->filled('harvest_id')) {
                $externalId = $category.'-'.$request->harvest_id.'-'.$amount.'-'.$hash;
            }

            // Check duplicate request
            $chk = Disbursement::where('external_id', $externalId)->first();
            if ($chk) {
                if ($chk->status === Disbursement::STATUS_FAILED) {
                    $externalId = $externalId.time();
                } else {
                    return back()->with('errorMsg', 'Sudah ada transaksi dengan nominal dan bank tujuan yang sama sebelumnya pada hari ini!');
                }
            }

            $amount = $amount - env('CASH_OUT_TOTAL_FEE');

            $description = strip_tags($request->description);
            $params = [
                'json' => [
                    'account_id' => env('XENDIT_ACCOUNT_ID'),
                    'external_id' => $externalId,
                    'bank_code' => $request->bank_code,
                    'account_holder_name' => $request->bank_account_holder_name,
                    'account_number' => $request->bank_account_number,
                    'description' => $description,
                    'amount' => $amount,
                    'email_to' => [$request->to_email],
                    'meta_data' => json_decode(json_encode($admin), true)
                ]
            ];

            $res = XenditRepository::createDisbursement($params);
            if (!isset($res->data->id)) return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
            if ($res->data->status === 'FAILED') return back()->with('errorMsg', 'Bank tujuan tidak ditemukan');

            $insert = new Disbursement;
            $insert->category = $category;
            $insert->external_id = $externalId;
            $insert->xendit_id = $res->data->xendit_id;
            $insert->status = $res->data->status;
            $insert->amount = $amount;
            $insert->fee = env('CASH_OUT_TOTAL_FEE');
            $insert->total = $amount + env('CASH_OUT_TOTAL_FEE');
            $insert->to_name = $request->to_name;
            $insert->to_email = $request->to_email;
            $insert->bank_code = $request->bank_code;
            $insert->bank_name = $request->bank_name;
            $insert->bank_account_holder_name = $request->bank_account_holder_name;
            $insert->bank_account_number = $request->bank_account_number;
            $insert->description = $description;
            $insert->xendit_data = json_decode(json_encode($res), true);
            $insert->transferred_by = json_decode(json_encode($admin), true);
            $insert->save();

            DB::commit();

            return back()->with('status', 'Tansfer ke '.$request->to_name.' sedang diproses');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
        }
    }
}
