<?php

namespace App\Http\Middleware;

use App\Enums\User\Role;
use App\Enums\User\Status;
use Closure;
use Illuminate\Http\Request;

class RedirectAuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authUser = $request->user();

        if (is_null($authUser)) {
            return $next($request);
        }

        if ($authUser->status->value !== Status::APPROVED->value) {
            return redirect()->route('account.notApproved');
        }

        if ($authUser->role->value === Role::ADMIN->value) {
            return redirect()->route('admin.users.withCurrentPlanFuelConsumption');
        }

        if ($authUser->role->value === Role::SUPER_ADMIN->value || $authUser->role->value === Role::OWNER->value) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
