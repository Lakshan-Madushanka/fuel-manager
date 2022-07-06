<?php

namespace App\Http\Controllers\FrontEnd\Admin\Qouta;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuotaCreateRequest;
use App\Models\Quota;

class QuotaController extends Controller
{
    public function index()
    {
        $plans = Quota::query()
            ->latest()
            ->paginate();

        return view('quota.index', compact('plans'));
    }

    public function create()
    {
        return view('quota.create');
    }

    public function store(QuotaCreateRequest $request)
    {
        $quota = Quota::create($request->validated());

        if ($quota->wasRecentlyCreated) {
            return back()->banner('Quota plan created successfully !');
        }

    }
}
