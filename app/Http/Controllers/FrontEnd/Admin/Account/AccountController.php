<?php

namespace App\Http\Controllers\FrontEnd\Admin\Account;

use App\Actions\Account\ApproveAccount;
use App\Actions\Account\BlockAccount;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    public function approve(User $user, ApproveAccount $approveAccount)
    {
        abort_if(Gate::denies('administrative'), 403);

        $approveAccount->execute($user);

        return back()->banner('Account successfully approved !');
    }

    public function block(User $user, BlockAccount $blockAccount)
    {
        abort_if(Gate::denies('administrative'), 403);

        $blockAccount->execute($user);

        return back()->banner('Account successfully blocked !');
    }
}
