<?php

namespace App\Actions\Account;

use App\Models\User;

class BlockAccount
{
    public function execute(User $user)
    {
        if ($user->approved) {
            $user->update(['approved' => false]);
        }

        return;
    }
}
