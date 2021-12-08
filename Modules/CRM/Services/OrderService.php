<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Order;

class OrderService extends ServiceContract {

    public function __construct(Order $order)
    {
        $this->model = $order;
    }
    
    public function create($data)
    {
        $product_ids = $data['product_ids'] ?? [];
        unset($data['product_ids']);
        $object = $this->model->create($data);
        
        $object->products()->attach($product_ids);
        
        return $object;
    }
    
    public function update($data)
    {
        $object = $this->model->find($data['id']);
        
        $product_ids = $data['product_ids'] ?? [];
        unset($data['product_ids']);
        
        $object->update($data);
        
        $object->products()->sync($product_ids);
    
        return $object;
    }
}