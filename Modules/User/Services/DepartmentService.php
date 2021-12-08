<?php
namespace Modules\User\Services;

use Modules\Core\Services\ServiceContract;
use Modules\User\Entities\Department;

class DepartmentService extends ServiceContract {

    public function __construct(Department $department)
    {
        $this->model = $department;
    }
    
}