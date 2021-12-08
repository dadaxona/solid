<?php

namespace Modules\WMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WMS\Http\Requests\ProductTransactionRequest;
use Modules\WMS\Services\ProductTransactionService;

class ProductTransactionController extends Controller
{
    public function __construct(protected ProductTransactionService $service){}

    public function store(ProductTransactionRequest $request)
    {
        $producttransaction = $this->service->create($request->validated());
        return response()->json(['item' => $producttransaction]);
    }
    public function update(ProductTransactionRequest $request)
    {
        $producttransaction = $this->service->update($request->validated());
        return response()->json(['item' => $producttransaction]);
    }
}