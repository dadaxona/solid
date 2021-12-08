<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\ClientRequest;
use Modules\CRM\Services\ClientService;

class ClientController extends Controller
{

    public function __construct(protected ClientService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'user_id', 'family_member_count', 'main_family_expense'],
                'relations' => [ 'user' ],
                'filters' => [
                    'user.full_name' => function($value){
                        return fn($query) => $query->whereHas('user', fn($query) => $query->where('full_name', 'like', '%'.$value.'%'));
                    },
                    'user.address_living' => function($value){
                        return fn($query) => $query->whereHas('user', fn($query) => $query->where('address_living', 'like', '%'.$value.'%'));
                    },
                    'user.phone' => function($value){
                        return fn($query) => $query->whereHas('user', fn($query) => $query->where('phone', 'like', '%'.$value.'%'));
                    },
                ]
            ]
        ];
    }

    public function store(ClientRequest $request)
    {
        $client = $this->service->create($request->validated());
        return response()->json(['item' => $client]);
    }

    public function update(ClientRequest $request)
    {
        $client = $this->service->update($request->validated());
        return response()->json(['item' => $client]);
    }

    public function show($id)
    {
        $item = $this->service->get($id);
        return response()->json(['item' => $item]);
    }
    public function find($identifier)
    {
        $item = $this->service->find($identifier);
        return response()->json(['item' => $item]);
    }
    
}