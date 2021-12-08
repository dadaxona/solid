<?php

namespace Modules\WMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\WMS\Http\Requests\ProductCategoryRequest;
use Modules\WMS\Services\ProductCategoryService;

class ProductCategoryController extends Controller
{
    public function __construct(protected ProductCategoryService $service){}

    public function store(ProductCategoryRequest $request)
    {
        $productCategory = $this->service->create($request->all());
        return response()->json(['item' => $productCategory]);
    }
    
    public function update(ProductCategoryRequest $request)
    {
        $productCategory = $this->service->update($request->all());
        return response()->json(['item' => $productCategory]);
    }
    
}