<?php

namespace App\Repositories\Payment;;

use App\Interfaces\Payment\BonusInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\Salary\FilterRequest;
use Illuminate\Support\Facades\DB;
use App\Models\{Employe, Branch, Company, Department, Position, Config};
use App\Traits\BugsnagTrait;
use Throwable;

class BonusRepository implements BonusInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index(FilterRequest $request)
    {
        try {
            $branchId = null;
            $branchName = null;

            // Find Config
            $config = Config::whereIn('name', [Config::BONUS_TYPE, Config::BONUS_PAYMENT_DATE, Config::BONUS_PAYMENT_PERCENTAGE])->get();

            // dd($config[0]);
            $query = Employe::query();
            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $query->where('name', 'ilike', "%{$name}%");
            }
            if ($request->filled('company_id')) {
                $query->ofCompany($request->company_id);
            }
            if ($request->filled('branch_id')) {
                $branchQuery = Branch::findOrFail($request->branch_id);
                $branchId = $branchQuery->id;
                $branchName = $branchQuery->name;
                $query->ofBranch($request->branch_id);
            }
            if ($request->filled('department_id')) {
                $query->ofDepartment($request->department_id);
            }
            if ($request->filled('position_id')) {
                $query->ofPosition($request->position_id);
            }

            $data = $query->paginate(self::PER_PAGE);

            $companies = Company::all();
            $departments = Department::all();
            $positions = Position::all();

            if(empty($config[0]))
            {
                // Set Config_date
                $config_type = $config;
                $config_type->value = null;

                // Set Config_date
                $config_date = $config;
                $config_date->value = null;
                
                // Set Config_percentage 
                $config_percentage = $config;
                $config_percentage->value = null;
            }else{
                // Set Config_date and percentage
                $config_type = $config[0];
                $config_date = $config[1];
                $config_percentage = $config[2];                
            }

            return view('payment.bonus.index', [
                'data' => $data,
                'config_date' => $config_date ? $config_date->value : $config_date,
                'config_percent' => $config_percentage,
                'config_type' => $config_type,
                'companies' => $companies,
                'departments' => $departments,
                'positions' => $positions,
                'branchId' => $branchId,
                'branchName' => $branchName
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function storeBonusConfig(Request $request)
    {
        DB::beginTransaction();
        try {

            // Find Config
            $configs = Config::whereIn('name', [Config::BONUS_TYPE, Config::BONUS_PAYMENT_DATE, Config::BONUS_PAYMENT_PERCENTAGE])->get();
            
            // Data For Config
            $datas = [
                ['name' => Config::BONUS_TYPE, 'value' => $request->bonus_type],
                ['name' => Config::BONUS_PAYMENT_DATE, 'value' => $request->bonus_date],
                ['name' => Config::BONUS_PAYMENT_PERCENTAGE, 'value' => $request->bonus_percent]
            ];

            if(empty($configs[0])){

                // Create Data
                foreach($datas as $data){
                    Config::create($data);
                }

            }else{

                // Update the type
                Config::where('name', Config::BONUS_TYPE)->update($datas[0]);

                // Update the date
                Config::where('name', Config::BONUS_PAYMENT_DATE)->update($datas[1]);

                // Update the percent
                Config::where('name', Config::BONUS_PAYMENT_PERCENTAGE)->update($datas[2]);
            }

            DB::commit();

            return back()->with('status', 'Set up tanggal pembayaran dan besar bonus berhasil');
        } catch (Throwable $e) {
            DB::rollback();
            $this->report($e);
            return back()->with('errorMsg', 'Terjadi kesalahan pada payment gateway'. $e);
        }
    }
}
