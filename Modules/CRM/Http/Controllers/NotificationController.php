<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\NotificationRequest;
use Modules\CRM\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $service){}

    public function store(NotificationRequest $request)
    {
        $notification = $this->service->create($request->all());
        return response()->json(['item' => $notification]);
    }

    public function update(NotificationRequest $request)
    {
        $notification = $this->service->update($request->all());
        return response()->json(['item' => $notification]);
    }
}