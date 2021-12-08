<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Http\Requests\DirectionRequest;
use Modules\Telegram\Services\DirectionService;

class DirectionController extends Controller
{
    public function __construct(protected DirectionService $service){ }

    public function store(DirectionRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(DirectionRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
}