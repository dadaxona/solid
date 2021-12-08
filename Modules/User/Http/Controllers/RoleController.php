<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\RoleRequest;
use Modules\User\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(protected RoleService $service){}
    
    public function store(RoleRequest $request)
    {
        $role = $this->service->create($request->validated());
        return response()->json(['item' => $role]);
    }
    public function update(RoleRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
    public function show($id)
    {
        $item = $this->service->get($id);
        $permissions = $item->permissions->pluck('id');
        $item = $item->toArray();
        $item['permissions'] = $permissions;
        return response()->json(['item' => $item]);
    }
}