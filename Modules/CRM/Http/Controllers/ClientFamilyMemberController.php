<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\ClientFamilyMemberRequest;
use Modules\CRM\Services\ClientFamilyMemberService;

class ClientFamilyMemberController extends Controller
{

    public function __construct(protected ClientFamilyMemberService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'client_id', 'full_name', 'work', 'salary'],
                'relations' => [ 'client' ]
            ]
        ];
    }
    public function store(ClientFamilyMemberRequest $request)
    {
        $client = $this->service->create($request->validated());
        return response()->json(['item' => $client]);
    }

    public function update(ClientFamilyMemberRequest $request)
    {
        $client = $this->service->update($request->validated());
        return response()->json(['item' => $client]);
    }

}