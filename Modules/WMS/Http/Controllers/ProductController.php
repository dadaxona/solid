<?php

namespace Modules\WMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WMS\Http\Requests\ProductRequest;
use Modules\WMS\Services\ProductService;


class ProductController extends Controller
{
    public function __construct(protected ProductService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'name', 'expiry_day', 'price', 'description', 'unit_id'],
                'relations' => [ 'unit' ]
            ]
        ];
    }

    public function store(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());
        return response()->json(['item' => $product]);
    }
    public function update(ProductRequest $request)
    {
        $product = $this->service->update($request->validated());
        return response()->json(['item' => $product]);
    }
}