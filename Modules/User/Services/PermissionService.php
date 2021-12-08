<?php
namespace Modules\User\Services;

use Modules\Core\Services\ServiceContract;
use Spatie\Permission\Models\Permission;

class PermissionService extends ServiceContract {

    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }
    
}