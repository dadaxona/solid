<?php
namespace Modules\WMS\Services;

use Modules\Core\Services\ServiceContract;
use Modules\WMS\Entities\Product;

class ProductService extends ServiceContract {

    public function __construct(Product $product)
    {
        $this->model = $product;
    }
    
    public function create($data)
    {
        $product_category_ids = $data['product_category_ids'] ?? [];
        unset($data['product_category_ids']);
        $object = $this->model->create($data);
        
        $object->categories()->attach($product_category_ids);
        
        return $object;
    }
    
    public function update($data)
    {
        $object = $this->model->find($data['id']);
        
        $product_category_ids = $data['product_category_ids'] ?? [];
        unset($data['product_category_ids']);
        
        $object->update($data);
        
        $object->categories()->sync($product_category_ids);
    
        return $object;
    }
}