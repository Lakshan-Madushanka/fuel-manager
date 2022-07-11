<?php

namespace App\Http\Controllers\FrontEnd\Admin\Qouta;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuotaCreateRequest;
use App\Models\Quota;
use App\Services\FuelQuotaService;
use Illuminate\Support\Facades\Gate;

class QuotaController extends Controller
{
    public function index()
    {
        $plans = Quota::query()
            ->orderByRaw('is_current_plan is false')
            ->paginate();

        return view('quota.index', compact('plans'));
    }

    public function create()
    {
        abort_if(Gate::denies('owner'), 403);

        return view('quota.create');
    }

    public function store(QuotaCreateRequest $request)
    {
        $validatedInputs = $request->validated();

        if (isset($validatedInputs['is_current_plan']) && $validatedInputs['is_current_plan']) {
            FuelQuotaService::makeAllPlansDeactivate();
        }

        $quota = Quota::create($request->validated());

        if ($quota->wasRecentlyCreated) {
            return back()->banner('Quota plan created successfully !');
        }
    }
}
