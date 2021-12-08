<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\ExpenseRequest;
use Modules\CRM\Services\ExpenseService;

class ExpenseController extends Controller
{
    public function __construct(protected ExpenseService $service){}

    public function store(ExpenseRequest $request)
    {
        $expense = $this->service->create($request->all());
        return response()->json(['item' => $expense]);
    }

    public function update(ExpenseRequest $request)
    {
        $expense = $this->service->update($request->all());
        return response()->json(['item' => $expense]);
    }
}