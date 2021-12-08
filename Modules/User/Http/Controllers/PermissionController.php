<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\PermissionRequest;
use Modules\User\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $service){}
    
    public function store(PermissionRequest $request)
    {
        $permission = $this->service->create($request->validated());
        return response()->json(['item' => $permission]);
    }
    public function update(PermissionRequest $request)
    {
        $permission = $this->service->update($request->validated());
        return response()->json(['item' => $permission]);
    }
}