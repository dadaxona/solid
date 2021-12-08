<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Http\Requests\RegionRequest;
use Modules\Telegram\Services\RegionService;

class RegionController extends Controller
{
    public function __construct(protected RegionService $service){ }

    public function store(RegionRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(RegionRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
}