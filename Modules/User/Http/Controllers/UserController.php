<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $service){
        $this->config = [
            'list' => [
                'columns' => ['id','image','full_name', 'gender', 'status', 'phone', 'address_living', 'email', 'created_at', 'birth_date'],
                'relations' => [ 'roles' ]
            ]
        ];
    }


    public function store(UserRequest $request)
    {
        $user = $this->service->create($request->validated());
        return response()->json(['item' => $user]);
    }
    public function update(UserRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
    public function show($id)
    {
        $item = $this->service->get($id);

        $roles = $item->roles->pluck('id');
        $item = $item->toArray();
        $item['roles'] = $roles;
        
        return response()->json(['item' => $item]);
    }
    public function find($identifier)
    {
        $item = $this->service->find($identifier);
        return response()->json(['item' => $item]);
    }
}