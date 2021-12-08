<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Http\Requests\AnnouncementRequest;
use Modules\Telegram\Services\AnnouncementService;

class AnnouncementController extends Controller
{
    public function __construct(protected AnnouncementService $service){ }

    public function store(AnnouncementRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(AnnouncementRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
}