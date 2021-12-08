<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\User\Services\DepartmentService;
use Modules\User\Http\Requests\DepartmentRequest;


class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $service){}

    public function store(DepartmentRequest $request)
    {
        $department = $this->service->create($request->validated());
        return response()->json(['item' => $department]);
    }
    public function update(DepartmentRequest $request)
    {
        $department = $this->service->update($request->validated());
        return response()->json(['item' => $department]);
    }
}