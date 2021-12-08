<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Http\Requests\TelegramChatRequest;
use Modules\Telegram\Services\TelegramChatService;

class TelegramChatController extends Controller
{
    public function __construct(protected TelegramChatService $service){ }

    public function store(TelegramChatRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(TelegramChatRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
}