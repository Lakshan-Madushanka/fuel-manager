<?php

namespace App\Actions\Account;

use App\Models\User;

class ApproveAccount
{
    public function execute(User $user)
    {
        if (! $user->approved) {
            $user->update(['approved' => true]);
        }

        return;
    }
}
