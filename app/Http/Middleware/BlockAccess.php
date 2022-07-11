<?php

namespace App\Http\Middleware;

use App\Enums\User\Status;
use Closure;
use Illuminate\Http\Request;

class BlockAccess
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

        if ($authUser->status->value !== Status::APPROVED->value) {
            return redirect()->route('account.notApproved');
        }

        return $next($request);
    }
}
