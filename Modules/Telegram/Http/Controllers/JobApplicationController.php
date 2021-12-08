<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Http\Requests\JobApplicationRequest;
use Modules\Telegram\Services\JobApplicationService;

class JobApplicationController extends Controller
{
    public function __construct(protected JobApplicationService $service){ }

    public function store(JobApplicationRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(JobApplicationRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
}