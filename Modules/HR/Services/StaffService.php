<?php
namespace Modules\HR\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Hr\Entities\Staff;

class StaffService extends ServiceContract {

    public function __construct(Staff $staff)
    {
        $this->model = $staff;
    }
    
}