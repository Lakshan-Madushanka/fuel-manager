<?php

namespace App\Http\Controllers\User;

use App\Actions\User\GetAllUsers;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(GetAllUsers $getAllUsers)
    {
        $users = $getAllUsers->execute();

        return view('user.consumption.index', ['users' => $users]);
    }
}
