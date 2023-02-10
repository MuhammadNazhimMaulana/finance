<?php

namespace App\Repositories;

use App\Interfaces\FeeRuleInterface;
use App\Models\{FeeRule};
use Illuminate\Support\Facades\DB;
use App\Repositories\XenditRepository;
use App\Traits\BugsnagTrait;
use Throwable;
use Carbon\Carbon;

class FeeRuleRepository implements FeeRuleInterface
{
    use BugsnagTrait;

    public function index()
    {
        try {
            $request = request();

            $res = FeeRule::query();

            // Payment Channels
            $payment_channels = XenditRepository::paymentChannels();

            // Fee rules
            $data = $res->orderBy('created_at', 'asc')->get();

            return view('config.fee_rule', [
                'data' => $data,
                'payment_channels' => $payment_channels
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $duplicate = FeeRule::where('payment_channel', $request->payment_channel)->first();
            if($duplicate) return back()->with('errorMsg', 'Fee Rule Sudah Ada');

            // Checking Unit
            if($request->unit == 'percent')
            {
                // If amount more than 100
                if ($request->amount > FeeRule::PERCENT_RULE)
                {
                    return back()->with('errorMsg', 'Persentase tidak boleh lebih dari 100');
                }
            }

            // If xendit_percentage_fee more than 100
            if ($request->xendit_percentage_fee > FeeRule::PERCENT_RULE)
            {
                return back()->with('errorMsg', 'Persentase tidak boleh lebih dari 100');
            }

            // Checking percent fee and flat fee
            if ($request->xendit_percentage_fee == null && $request->xendit_flat_fee == null || $request->xendit_percentage_fee == 0 && $request->xendit_flat_fee == 0)
            {
                return back()->with('errorMsg', 'Percent Fee atau Flat Fee saja yang boleh kosong');
            }
            
            // Parameter untuk membuat Fee Rule
            $params = [
                'json' => [
                    'name' => $request->name,
                    'description' =>  $request->filled('description') ? $request->description : null,
                    'unit' => $request->unit,
                    'amount' => (int) $request->margin,
                    'currency' => 'IDR'
                ]
            ];

            // Creating Fee Rule
            $rule = XenditRepository::createFeeRule($params);
                
            $res = new FeeRule();
            $res->xendit_fee_rule_id = $rule->data->xendit_id;
            $res->rule_name = $rule->data->name;
            $res->description = $rule->data->description;
            $res->margin = $rule->data->amount;
            $res->payment_channel = $request->payment_channel;
            $res->currency = $rule->data->currency;
            $res->xendit_unit = $rule->data->unit;
            $res->pajak = $request->pajak;
            $res->xendit_percentage_fee = $request->xendit_percentage_fee ? $request->xendit_percentage_fee : 0;
            $res->xendit_flat_fee = $request->xendit_flat_fee ? $request->xendit_flat_fee : 0;

            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Fee Rule Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {

            // Finding Fee Rule
            $res = FeeRule::find($id);
            if(!$res) return back()->with('errorMsg', 'Fee Rule Tidak ditemukan');

            // Deleting Fee Rule
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Fee Rule Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}
