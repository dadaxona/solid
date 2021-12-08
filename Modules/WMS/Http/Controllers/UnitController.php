<?php

namespace Modules\WMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WMS\Http\Requests\UnitRequest;
use Modules\WMS\Services\UnitService;


class UnitController extends Controller
{
    public function __construct(protected UnitService $service){}

    public function store(UnitRequest $request)
    {
        $unit = $this->service->create($request->validated());
        return response()->json(['item' => $unit]);
    }
    public function update(UnitRequest $request)
    {
        $unit = $this->service->update($request->validated());
        return response()->json(['item' => $unit]);
    }
}