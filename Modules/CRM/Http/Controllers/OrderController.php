<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\OrderRequest;
use Modules\CRM\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(protected OrderService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'total', 'status', 'client_id'],
                'relations' => [ 'client' ]
            ]
        ];
    }

    public function store(OrderRequest $request)
    {
        $order = $this->service->create($request->validated());
        return response()->json(['item' => $order]);
    }

    public function update(OrderRequest $request)
    {
        $order = $this->service->update($request->validated());
        return response()->json(['item' => $order]);
    }
}