<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Http\Requests\TelegramBotRequest;
use Modules\Telegram\Services\TelegramBotService;

class TelegramBotController extends Controller
{
    public function __construct(protected TelegramBotService $service){ }

    public function store(TelegramBotRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(TelegramBotRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
}