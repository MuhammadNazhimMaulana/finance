<?php

namespace App\Repositories\Employe;;

use App\Interfaces\Employe\BankInterface;
use App\Http\Requests\Employe\BankRequest;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use App\Repositories\XenditRepository;
use App\Models\{EmployeBank, Employe};
use Throwable;

class BankRepository implements BankInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index(int $id)
    {
        try {
            $employe = Employe::findOrFail($id);
            $data = EmployeBank::where('employe_id', $id)->paginate(self::PER_PAGE);

            $banks = XenditRepository::banks();

            return view('employe.bank', [
                'employe' => $employe,
                'data' => $data,
                'banks' => $banks
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store(BankRequest $request)
    {
        DB::beginTransaction();
        try {

            $res = new EmployeBank;
            $res->employe_id = $request->employe_id;
            $res->code = $request->code;
            $res->name = $request->name;
            $res->account_holder_name = $request->account_holder_name;
            $res->account_number = $request->account_number;

            // Find Employe Bank with status Active
            if($request->status == EmployeBank::STATUS_ACTIVE)
            {
                $employeBank = EmployeBank::where('employe_id', $request->employe_id)->where('status', EmployeBank::STATUS_ACTIVE)->first();
                
                // If no active Bank
                if(!$employeBank)
                {
                    $res->status = $request->status;        
                }else{
                    $res->status = EmployeBank::STATUS_INACTIVE;        
                }
            }

            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Bank Created'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public function update(int $id, BankRequest $request)
    {
        DB::beginTransaction();
        try {
            $res = EmployeBank::findOrFail($id);
            $res->employe_id = $request->employe_id;
            $res->code = $request->code;
            $res->name = $request->name;
            $res->account_holder_name = $request->account_holder_name;
            $res->account_number = $request->account_number;

            // Find Employe Bank with status Active
            if($request->status == EmployeBank::STATUS_ACTIVE)
            {
                $employeBank = EmployeBank::where('employe_id', $request->employe_id)->where('status', EmployeBank::STATUS_ACTIVE)->first();
                
                // If no active Bank
                if(!$employeBank)
                {
                    $res->status = $request->status;        
                }else{
                    // Change Active to Inactive
                    $employeBank->status = EmployeBank::STATUS_INACTIVE;
                    $employeBank->save();

                    // One Inactive become Active
                    $res->status = $request->status;        
                }
            }

            $res->save();

            DB::commit();

            return back()->with('successMsg', __('Bank Updated'));
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
            $res = EmployeBank::findOrFail($id);
            $res->delete();

            DB::commit();

            return back()->with('successMsg', __('Bank Deleted'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }
}
