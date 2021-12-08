<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\HR\Services\StaffPositionService;
use Modules\HR\Http\Requests\StaffPositionRequest;


class StaffPositionController extends Controller
{
    public function __construct(protected StaffPositionService $service){}

    public function store(StaffPositionRequest $request)
    {
        $staffposition = $this->service->create($request->validated());
        return response()->json(['item' => $staffposition]);
    }
    public function update(StaffPositionRequest $request)
    {
        $staffposition = $this->service->update($request->validated());
        return response()->json(['item' => $staffposition]);
    }
}