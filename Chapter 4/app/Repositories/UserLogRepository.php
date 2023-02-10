<?php

namespace App\Repositories;;

use App\Interfaces\UserLogInterface;
use App\Traits\BugsnagTrait;
use App\Models\UserLog;
use Throwable;

class UserLogRepository implements UserLogInterface
{
    use BugsnagTrait;

    const PER_PAGE = 20;

    public function index()
    {
        try {
            $request = request();

            $res = UserLog::query();

            if ($request->filled('name')) {
                $name = strip_tags($request->name);
                $res->where('action', 'ilike', "%{$name}%");
            }

            $data = $res->paginate(self::PER_PAGE);

            return view('userlog.index', [
                'data' => $data
            ]);
        } catch (Throwable $e) {
            abort(400, $e->getMessage());
        }
    }
}
