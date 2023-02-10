<?php

namespace App\Repositories\User;

use App\Interfaces\User\{ProfileInterface, UserLogInterface};
use App\Http\Requests\User\Profile\UpdateRequest;
use App\User;
use App\Events\ProfileUpdated;
use Illuminate\Support\Facades\DB;
use App\Traits\BugsnagTrait;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ProfileRepository implements ProfileInterface
{
    use BugsnagTrait;

    public function __construct(UserLogInterface $userLogInterface)
    {
        $this->userLogInterface = $userLogInterface;
    }

    public function index()
    {
        $logs = $this->userLogInterface->index();

        return view('user.profile', [
            'logs' => $logs
        ]);
    }

    public function update(UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->user()->id);
            if (!$user) return back()->with('errorMsg', __('User tidak ditemukan'));
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            event(new ProfileUpdated($user));

            DB::commit();

            if ($request->filled('password')) {
                Auth::logoutOtherDevices($request->password);
            }

            return back()->with('successMsg', __('Perubahan berhasil disimpan'));
        } catch (Throwable $e) {
            DB::rollBack();

            $this->report($e);

            return back()->with('errorMsg', __('Terjadi kesalahan'));
        }
    }
}
