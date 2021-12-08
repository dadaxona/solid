<?php
namespace Modules\WMS\Services;

use Modules\Core\Services\ServiceContract;
use Modules\WMS\Entities\Warehouse;

class WarehouseService extends ServiceContract {

    public function __construct(Warehouse $warehouse)
    {
        $this->model = $warehouse;
    }
    public function create($data)
    {
        $user_ids = $data['user_ids'] ?? [];
        unset($data['user_ids']);
        $object = $this->model->create($data);
        
        $object->users()->attach($user_ids);
        
        return $object;
    }
    
    public function update($data)
    {
        $object = $this->model->find($data['id']);
        
        $user_ids = $data['user_ids'] ?? [];
        unset($data['user_ids']);
        
        $object->update($data);
        
        $object->users()->sync($user_ids);
    
        return $object;
    }
}