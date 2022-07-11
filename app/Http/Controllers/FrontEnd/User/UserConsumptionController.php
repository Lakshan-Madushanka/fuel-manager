<?php

namespace App\Http\Controllers\FrontEnd\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserConsumptionController extends Controller
{
    public function index(User $user)
    {
        abort_if(Gate::denies('administrative') && Auth::id() !== $user->id, 403);

        $consumptions = $user->consumptions()->cursorPaginate();

        return view('user.consumption.show', compact('consumptions'));
    }
}
