<?php

namespace App\Repositories\Payment;;

use App\Interfaces\Payment\TopupInterface;
use App\Http\Requests\Payment\Topup\StoreRequest;
use App\Traits\BugsnagTrait;
use App\Repositories\XenditRepository;
use Illuminate\Support\Facades\DB;
use App\Models\{Topup, FeeRule};
use Carbon\Carbon;
use Throwable;

class TopupRepository implements TopupInterface
{
    use BugsnagTrait;

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $requestedAmount = (int) str_replace('.','', $request->amount);

            $externalId = 'topup-'.$request->payment_method.'-'.$requestedAmount.'-'.$request->user()->id.'-'.time();
            $description = 'Topup system balance oleh '.strtoupper($request->user()->name);
            $params = [
                'json' => [
                    'account_id' => env('XENDIT_ACCOUNT_ID'),
                    'external_id' => $externalId,
                    'amount' => $requestedAmount,
                    'description' => $description,
                    'payment_methods' => [$request->payment_method]
                ]
            ];

            // Checking Existance of Payment Method
            if($params['json']['payment_methods'][0])
            {
                $feeRule = FeeRule::where('payment_channel', $request->payment_method)->first();
                if($feeRule){
                    // Adding Fee rule
                    $params['json']["with-fee-rule"] = $feeRule->xendit_fee_rule_id;

                    // Checking Fee Percent
                    if($feeRule->xendit_percentage_fee != 0)
                    {
                        $percent_fee = $requestedAmount * $feeRule->xendit_percentage_fee/100;
                    }else{
                        $percent_fee = 0;
                    }

                    // Chacking Fee Flat
                    if($feeRule->xendit_flat_fee != 0)
                    {
                        $flat_fee = $feeRule->xendit_flat_fee;
                    }else{
                        $flat_fee = 0;
                    }

                    // Checking Fee Rule
                    if($feeRule->xendit_unit == strtoupper(FeeRule::PERCENT))
                    {
                        $fee_rule = $requestedAmount * $feeRule->margin/100;
                    }else{
                        $fee_rule = $feeRule->margin;
                    }

                    // Totaling Fee
                    $payment_fee = ceil($percent_fee) + $flat_fee + $fee_rule;
                    
                    // Tax Fee
                    $tax_fee = ceil(($percent_fee + $flat_fee) * ($feeRule->pajak/100));

                    // Adding Fee
                    if($percent_fee == 0){
                        $params['json']["fees"] = [array('type' => 'Payment Fee', 'value' => $payment_fee + $tax_fee)];
                    }
                    
                    if($percent_fee == 0){
                        // Amount plus Fee
                        $params['json']["amount"] = $requestedAmount + $payment_fee + $tax_fee;
                    }else{
                        $params['json']["amount"] = $requestedAmount;
                    }
                    
                
                    // All Fee Total
                    $fee = $payment_fee + $tax_fee;
                }else{
                    $fee = 0;
                }

            }

            $res = XenditRepository::createInvoice($params);

            if (!isset($res->data->id)) return back()->with('errorMsg', 'PG::Request payment gateway gagal');

            $topup = new Topup;
            $topup->user_id = $request->user()->id;
            $topup->external_id = $externalId;
            $topup->xendit_id = $res->data->id;
            $topup->amount = $percent_fee ? $requestedAmount - $fee : $requestedAmount;
            $topup->fee = $fee;
            $topup->total = $percent_fee ? $topup->amount + $fee : $requestedAmount + $fee;
            $topup->description = $description;
            $topup->xendit_data = json_decode(json_encode($res), true);
            $topup->user_data = json_decode(json_encode($request->user()), true);
            $topup->payment_url = $res->data->invoice_url;
            $topup->expired_at = Carbon::now()->addDays(3)->toDateTimeString();
            $topup->save();

            DB::commit();

            return back()->with('invoiceurl', $res->data->invoice_url);
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'SYSTEM::Request payment gateway gagal');
        }
    }
}
