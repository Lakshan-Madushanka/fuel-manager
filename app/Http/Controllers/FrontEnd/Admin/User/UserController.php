<?php

namespace App\Http\Controllers\FrontEnd\Admin\User;

use App\Actions\User\GetAllUsersWithCurrentPlanFuelConsumption;
use App\Filters\User\Search;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('administrative'), 403);

        $query = app(Pipeline::class)
            ->send(User::query())
            ->through([
                Search::class,
            ])
        ->thenReturn();

        $users = $query
            ->latest()
            ->paginate();

        return view('user.index', ['users' => $users]);
    }

    public function withCurrentPlanFuelConsumption(GetAllUsersWithCurrentPlanFuelConsumption $getAllUsers)
    {
        abort_if(Gate::denies('administrative') && Gate::denies('admin'), 403);

        $users = $getAllUsers->execute();

        return view('user.consumption.index', ['users' => $users]);
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('administrative'), 403);

        if ($user->delete()) {
            return back()->banner('User successfully deleted !');
        };
    }

    public function massDelete(Request $request)
    {
        abort_if(Gate::denies('administrative'), 403);

        $request->validate(['deleteIds' => 'required']);

        $deleteIds = gettype($request->deleteIds) === 'string'
            ? explode(',', $request->deleteIds)
            : $request->deleteIds;

        $ids = User::query()
            ->whereIn('id', $deleteIds)
            ->delete();

        return back()->banner('Selected users successfully deleted !');
    }
}
