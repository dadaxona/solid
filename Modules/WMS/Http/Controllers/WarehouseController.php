<?php

namespace Modules\WMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WMS\Http\Requests\WarehouseRequest;
use Modules\WMS\Services\WarehouseService;

class WarehouseController extends Controller
{
    public function __construct(protected WarehouseService $service){}

    public function store(WarehouseRequest $request)
    {
        $warehouse = $this->service->create($request->validated());
        return response()->json(['item' => $warehouse]);
    }
    public function update(WarehouseRequest $request)
    {
        $warehouse = $this->service->update($request->validated());
        return response()->json(['item' => $warehouse]);
    }
}