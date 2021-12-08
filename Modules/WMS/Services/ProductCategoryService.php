<?php
namespace Modules\WMS\Services;

use Modules\Core\Services\ServiceContract;
use Modules\WMS\Entities\ProductCategory;

class ProductCategoryService extends ServiceContract {

    public function __construct(ProductCategory $productCategory)
    {
        $this->model = $productCategory;
    }
    
}