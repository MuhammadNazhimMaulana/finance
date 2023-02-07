<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserLogInterface;
use App\User;
use App\Models\UserLog;
use Throwable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\BugsnagTrait;

class UserLogRepository implements UserLogInterface
{
    use BugsnagTrait;

    const EXPIRED_MONTH = 3;

    public function index()
    {
        return UserLog::where('user_id', auth()->user()->id)->take(5)->get();
    }

    public function store(int $userId, string $action, ?string $ipAddress, ?string $userAgent)
    {
        DB::beginTransaction();
        try {
            $user = User::find($userId);
            if (!$user) return;

            $log = new UserLog;
            $log->user_id = $user->id;
            $log->user_name = $user->name;
            $log->user_email = $user->email;
            $log->action = strtoupper($action);
            if ($ipAddress) {
                $log->ip_address = $ipAddress;
            }
            if ($userAgent) {
                $log->user_agent = $userAgent;
            }
            $log->expired_at = Carbon::now()->addMonths(self::EXPIRED_MONTH)->format('Y-m-d');
            $log->save();

            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();

            $this->report($e);

            return;
        }
    }
}
