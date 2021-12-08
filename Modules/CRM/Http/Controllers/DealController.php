<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\DealRequest;
use Modules\CRM\Services\DealService;

class DealController extends Controller
{
    public function __construct(protected DealService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'status', 'client_id', 'warehouse_id'],
                'relations' => [ 'client.user', 'products', 'warehouse' ],
                'filters' => [
                    'client.user.full_name' => function($value){
                        return fn($q1) => $q1->whereHas('client', fn($q2) => $q2->whereHas('user', fn($q3) => $q3->where('full_name', 'like', '%'.$value.'%')));
                    },
                ]
            ],
        ];
    }
    public function store(DealRequest $request)
    {
        $deal = $this->service->create($request->validated());
        return response()->json(['item' => $deal]);
    }

    public function update(DealRequest $request)
    {
        $deal = $this->service->update($request->validated());
        return response()->json(['item' => $deal]);
    }
}