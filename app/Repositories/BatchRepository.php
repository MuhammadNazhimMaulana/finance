<?php

namespace App\Repositories;;

use App\Interfaces\BatchInterface;
use App\Http\Requests\Payment\Batch\StoreRequest;
use App\Repositories\XenditRepository;
use App\Traits\BugsnagTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use App\Models\{Batch, Contact, Disbursement};
use Throwable;

class BatchRepository implements BatchInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;
    const CATEGORY = 'BATCH';
    const PROOF = 'Bukti Transaksi';
    const TYPE = 'CASH';
    const DEFAULT_NUMBER = 0;

    public function index()
    {
        try {
            $request = request();

            $contacts = Contact::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $contacts->where('name', 'ilike', "%{$name}%");
            }

            $contacts = Contact::paginate(self::PER_PAGE);

            $data = Batch::with([
                    'disbursements' => function ($q){
                        $q->orderBy('created_at', 'asc');
                    }
                ])->orderBy('created_at', 'desc')->paginate(self::PER_PAGE);

            $latest = Batch::orderBy('created_at', 'desc')->first();

            // dd($data[0]->disbursements);

            return view('batch.index', [
                'contacts' => $contacts,
                'latest' => $latest,
                'data' => $data
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $admin = $request->user();
            
            // Validate Pin
            if (!$admin->pin) return back()->with('errorMsg', 'PIN Anda Salah!');
            if (!Hash::check($request->pin, $admin->pin->value)) return back()->with('errorMsg', 'PIN Anda Salah!');

            // Getting Balance
            $res = XenditRepository::getBalance(self::TYPE);

            $keys = [];
            foreach(array_keys($request->all()) as $key)
            {
                $word = preg_replace('/[0-9]+/', '', $key);

                if($word === 'contact')
                {
                    array_push($keys, $key);
                } 
            }

            // Checking the contact
            if(empty($keys) == true) return back()->with('errorMsg', 'Kontak Pilihan Tidak Boleh kosong');

            $names = [];
            $balance_checker = [];
            for($i = 1; $i <= count($keys); $i++)
            {   
                if($request->all()[$keys[$i - 1]] != null)
                {
                    // Real Fee Number
                    $fee_number = (str_replace('contact', '', $keys[$i-1]));

                    array_push($names, $request->all()[$keys[$i - 1]]);

                    // Nominal
                    if($request->all()['total_fee'.$fee_number] == null) return back()->with('errorMsg', 'Nominal Tidak Boleh kosong');

                    // Preparing Balance Checker
                    $total = (int) str_replace('.', '', $request->all()['total_fee'.$fee_number]);
                    array_push($balance_checker, $total);
                }
            }

            // Checking Balance
            if(array_sum($balance_checker) > $res->data->balance) return back()->with('errorMsg', 'Saldo Cash Tidak Mencukupi');

            $total_amount = [];
            $disbursment_id = [];
            foreach($names as $name)
            {
                $contact = Contact::where('name', $name)->first();
                
                // Disbursement
                $externalId = self::CATEGORY.'-'.$contact->id.'-'.Carbon::now()->format('Y-m').'-'.md5(request()->url() . Carbon::now());
                
                // Check duplicate request
                $chk = Disbursement::where('external_id', $externalId)->first();
                if ($chk) {
                    if ($chk->status === Disbursement::STATUS_FAILED) {
                        $externalId = $externalId.time();
                    } else {
                        return back()->with('errorMsg', 'Sudah ada transaksi dengan nominal dan bank tujuan yang sama sebelumnya pada hari ini!');
                    }
                }
                
                $amount = (int) str_replace('.', '', $request->all()['total_fee'.$contact->id]);

                // Adding amount
                array_push($total_amount, $amount - env('CASH_OUT_TOTAL_FEE'));

                $description = strtoupper('Bayar '.$contact->name.' bulan '.Carbon::now()->format('F').' '.Carbon::now()->format('Y').' Rp'.CurrencyHelper::toIDR($amount).' ke '.$contact->bank_name.' no rek '.$contact->bank_account_number.' atas nama '.$contact->bank_account_holder_name).' diproses oleh '.$admin->name;

                $params = [
                    'json' => [
                        'account_id' => env('XENDIT_ACCOUNT_ID'),
                        'external_id' => $externalId,
                        'bank_code' => $contact->bank_code,
                        'account_holder_name' => $contact->bank_account_holder_name,
                        'account_number' => $contact->bank_account_number,
                        'description' => $description,
                        'amount' => (int) $amount - env('CASH_OUT_TOTAL_FEE'),
                        'email_to' => [$contact->email],
                        'meta_data' => json_decode(json_encode($admin), true)
                    ]
                ];

                $res = XenditRepository::createDisbursement($params);
                if (!isset($res->data->id)) return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
                if ($res->data->status === 'FAILED') return back()->with('errorMsg', 'Bank tujuan tidak ditemukan');


                $insert = new Disbursement;
                $insert->category = self::CATEGORY;
                $insert->external_id = $externalId;
                $insert->xendit_id = $res->data->xendit_id;
                $insert->status = $res->data->status;
                $insert->amount = (int) $amount - env('CASH_OUT_TOTAL_FEE');
                $insert->fee = env('CASH_OUT_TOTAL_FEE');
                $insert->total = $amount;
                $insert->to_name = $contact->name;
                $insert->to_email = $contact->email;
                $insert->bank_code = $contact->bank_code;
                $insert->bank_name = $contact->bank_name;
                $insert->bank_account_holder_name = $contact->bank_account_holder_name;
                $insert->bank_account_number = $contact->bank_account_number;
                $insert->description = $description;
                $insert->xendit_data = json_decode(json_encode($res), true);
                $insert->transferred_by = json_decode(json_encode($admin), true);
                $insert->save();

                // Adding id into an array
                array_push($disbursment_id, $insert->id);

            }

            $batch = new Batch;
            $batch->remark = $request->remark;
            $batch->total_amount = array_sum($total_amount);
            $batch->total_item = count($names);
            $batch->total_fee = count($names) * env('CASH_OUT_TOTAL_FEE');
            $batch->complete_count = self::DEFAULT_NUMBER;
            $batch->pending_count = count($names);
            $batch->failed_count = self::DEFAULT_NUMBER;
            $batch->total = (array_sum($total_amount)) + (count($names) * env('CASH_OUT_TOTAL_FEE'));
            $batch->save();

            foreach($disbursment_id as $id)
            {
                $batch->disbursements()->syncWithoutDetaching([$id]);
            }
            
            DB::commit();

            return back()->with('status', 'Transaksi Sedang Diproses');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            dd($e);
            return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
        }
    }

    public function retransfer($request)
    {
        DB::beginTransaction();
        try {
            $admin = $request->user();

            // Finding Disbusrement
            $batch = Batch::find($request->batch_id);
            if(!$batch) return back()->with('errorMsg', 'Transaksi Tidak Ditemukan');

            // Finding Disbursement
            $disbursements = $batch->disbursements;

            $pending = [];
            foreach($disbursements as $disbursement)
            {
                if($disbursement->status === Disbursement::STATUS_FAILED)
                {                   
                    // Disbursement
                    $externalId = self::CATEGORY.'-'.$disbursement->id.'-'.Carbon::now()->format('Y-m').'-'.md5(request()->url() . Carbon::now());
                        
                    // Check duplicate request
                    $chk = Disbursement::where('external_id', $externalId)->first();
                    if ($chk) {
                        if ($chk->status === Disbursement::STATUS_FAILED) {
                            $externalId = $externalId.time();
                        } else {
                            return back()->with('errorMsg', 'Sudah ada transaksi dengan nominal dan bank tujuan yang sama sebelumnya pada hari ini!');
                        }
                    }
                        
                    $amount = (int) $disbursement->amount;
        
                    $description = strtoupper('Bayar '.$disbursement->name.' bulan '.Carbon::now()->format('F').' '.Carbon::now()->format('Y').' Rp'.CurrencyHelper::toIDR($amount).' ke '.$disbursement->bank_name.' no rek '.$disbursement->bank_account_number.' atas nama '.$disbursement->bank_account_holder_name).' diproses oleh '.$admin->name;
        
                    $params = [
                        'json' => [
                            'account_id' => env('XENDIT_ACCOUNT_ID'),
                            'external_id' => $externalId,
                            'bank_code' => $disbursement->bank_code,
                            'account_holder_name' => $disbursement->bank_account_holder_name,
                            'account_number' => $disbursement->bank_account_number,
                            'description' => $description,
                            'amount' => $amount,
                            'email_to' => [$disbursement->to_email],
                            'meta_data' => json_decode(json_encode($admin), true)
                        ]
                    ];
                    
                    $res = XenditRepository::createDisbursement($params);
                    if (!isset($res->data->id)) return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
                    if ($res->data->status === 'FAILED') return back()->with('errorMsg', 'Bank tujuan tidak ditemukan');
                    
                    // Update Disbursement
                    $update = Disbursement::find($disbursement->id);
                    $update->external_id = $externalId;
                    $update->xendit_id = $res->data->xendit_id;
                    $update->status = $res->data->status;
                    $update->description = $description;
                    $update->xendit_data = json_decode(json_encode($res), true);
                    $update->save();

                    // Adding one to pending
                    array_push($pending, $update->id);
                }
            }
            
            // Updating Batch
            $batch->complete_count = $batch ? $batch->complete_count : self::DEFAULT_NUMBER;
            $batch->pending_count = count($pending);
            $batch->failed_count = self::DEFAULT_NUMBER;
            $batch->save();
           
            DB::commit();

            return back()->with('status', 'Transaksi Sedang Diproses');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            dd($e);
            return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway');
        }
    }

    // PDF Viewer
    public function transferProof(int $disbursement_id)
    {
        try {
            // Getting Disbursement Data
            $disbursement = Disbursement::find($disbursement_id);

            // Sender Data
            $sender = (object) $disbursement->transferred_by;

            // Calling DOMPDF
            $pdf = App::make('dompdf.wrapper');

            // Loading view using DOMPSDF
            $pdf->loadview('batch.pdf', [
                'disbursement' => $disbursement, 
                'sender' => $sender, 
                'title' => self::PROOF, 
                ])->setpaper('A4', 'portrait');
            
            // Showing The pdf
            return $pdf->stream('Payment Proof.pdf');
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }
}
