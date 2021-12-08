<?php
namespace Modules\User\Services;

use Modules\Core\Services\ServiceContract;
use Spatie\Permission\Models\Role;

class RoleService extends ServiceContract {

    public function __construct(Role $role)
    {
        $this->model = $role;
    }
    
    public function create($data){
        $permission_ids = $data['permissions']??null;
        unset($data['permissions']);

        $role = $this->model->create($data);
        $role->permissions()->attach($permission_ids);
        
        return $role;
    }
    
    public function update($data)
    {
        $permission_ids = $data['permissions']??null;
        unset($data['permissions']);
        
        $role = $this->model->find($data['id']);
        $role->update($data);
        $role->permissions()->sync($permission_ids);
        
        return $role;
    }
    
}